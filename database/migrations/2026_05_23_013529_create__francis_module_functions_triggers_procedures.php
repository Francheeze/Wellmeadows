<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Functions ──────────────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_stock_status(
                p_quantity INT,
                p_reorder  INT
            ) RETURNS VARCHAR AS \$\$
            BEGIN
                IF p_quantity = 0            THEN RETURN 'OUT OF STOCK';
                ELSIF p_quantity <= p_reorder THEN RETURN 'LOW';
                ELSE                              RETURN 'OK';
                END IF;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_requisition_total_cost(
                p_requisition_number INT
            ) RETURNS NUMERIC AS \$\$
            DECLARE
                v_drug_cost   NUMERIC := 0;
                v_supply_cost NUMERIC := 0;
            BEGIN
                SELECT COALESCE(SUM(rdi.quantity_required * pi.cost_per_unit), 0)
                INTO v_drug_cost
                FROM requisition_drug_items rdi
                JOIN pharmaceutical_items pi ON pi.drug_number = rdi.drug_number
                WHERE rdi.requisition_number = p_requisition_number;

                SELECT COALESCE(SUM(rsi.quantity_required * si.cost_per_unit), 0)
                INTO v_supply_cost
                FROM requisition_supply_items rsi
                JOIN supply_items si ON si.item_number = rsi.item_number
                WHERE rsi.requisition_number = p_requisition_number;

                RETURN v_drug_cost + v_supply_cost;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_low_stock_items()
            RETURNS TABLE (
                item_type         TEXT,
                item_id           TEXT,
                item_name         VARCHAR,
                quantity_in_stock INT,
                reorder_level     INT,
                supplier_name     VARCHAR
            ) AS \$\$
            BEGIN
                RETURN QUERY
                    SELECT 'Drug'::TEXT, pi.drug_number::TEXT, pi.drug_name,
                           pi.quantity_in_stock, pi.reorder_level, s.supplier_name
                    FROM pharmaceutical_items pi
                    JOIN suppliers s ON s.supplier_number = pi.supplier_number
                    WHERE pi.quantity_in_stock <= pi.reorder_level
                    UNION ALL
                    SELECT 'Supply'::TEXT, si.item_number::TEXT, si.item_name,
                           si.quantity_in_stock, si.reorder_level, s.supplier_name
                    FROM supply_items si
                    JOIN suppliers s ON s.supplier_number = si.supplier_number
                    WHERE si.quantity_in_stock <= si.reorder_level
                    ORDER BY quantity_in_stock ASC;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_supplier_inventory_value(
                p_supplier_number INT
            ) RETURNS NUMERIC AS \$\$
            DECLARE
                v_total NUMERIC := 0;
            BEGIN
                SELECT COALESCE(SUM(quantity_in_stock * cost_per_unit), 0)
                INTO v_total
                FROM pharmaceutical_items
                WHERE supplier_number = p_supplier_number;

                SELECT v_total + COALESCE(SUM(quantity_in_stock * cost_per_unit), 0)
                INTO v_total
                FROM supply_items
                WHERE supplier_number = p_supplier_number;

                RETURN v_total;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION is_drug_active_for_patient(
                p_patient_number INT,
                p_drug_number    INT
            ) RETURNS BOOLEAN AS \$\$
            BEGIN
                RETURN EXISTS (
                    SELECT 1 FROM patient_medications
                    WHERE patient_number = p_patient_number
                      AND drug_number    = p_drug_number
                      AND start_date    <= CURRENT_DATE
                      AND (finish_date IS NULL OR finish_date >= CURRENT_DATE)
                );
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // ── Trigger Functions ───────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_deduct_drug_stock()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF (SELECT quantity_in_stock FROM pharmaceutical_items
                    WHERE drug_number = NEW.drug_number) < NEW.quantity_required THEN
                    RAISE EXCEPTION
                        'Insufficient stock for drug %. Available: %, Requested: %',
                        NEW.drug_number,
                        (SELECT quantity_in_stock FROM pharmaceutical_items WHERE drug_number = NEW.drug_number),
                        NEW.quantity_required;
                END IF;
                UPDATE pharmaceutical_items
                SET quantity_in_stock = quantity_in_stock - NEW.quantity_required
                WHERE drug_number = NEW.drug_number;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_drug_requisition_insert
            BEFORE INSERT ON requisition_drug_items
            FOR EACH ROW EXECUTE FUNCTION trg_deduct_drug_stock();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_deduct_supply_stock()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF (SELECT quantity_in_stock FROM supply_items
                    WHERE item_number = NEW.item_number) < NEW.quantity_required THEN
                    RAISE EXCEPTION
                        'Insufficient stock for supply item %. Available: %, Requested: %',
                        NEW.item_number,
                        (SELECT quantity_in_stock FROM supply_items WHERE item_number = NEW.item_number),
                        NEW.quantity_required;
                END IF;
                UPDATE supply_items
                SET quantity_in_stock = quantity_in_stock - NEW.quantity_required
                WHERE item_number = NEW.item_number;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_supply_requisition_insert
            BEFORE INSERT ON requisition_supply_items
            FOR EACH ROW EXECUTE FUNCTION trg_deduct_supply_stock();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_restore_drug_stock()
            RETURNS TRIGGER AS \$\$
            BEGIN
                UPDATE pharmaceutical_items
                SET quantity_in_stock = quantity_in_stock + OLD.quantity_required
                WHERE drug_number = OLD.drug_number;
                RETURN OLD;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_drug_requisition_delete
            AFTER DELETE ON requisition_drug_items
            FOR EACH ROW EXECUTE FUNCTION trg_restore_drug_stock();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_restore_supply_stock()
            RETURNS TRIGGER AS \$\$
            BEGIN
                UPDATE supply_items
                SET quantity_in_stock = quantity_in_stock + OLD.quantity_required
                WHERE item_number = OLD.item_number;
                RETURN OLD;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_supply_requisition_delete
            AFTER DELETE ON requisition_supply_items
            FOR EACH ROW EXECUTE FUNCTION trg_restore_supply_stock();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_adjust_drug_stock_on_update()
            RETURNS TRIGGER AS \$\$
            DECLARE v_diff INT;
            BEGIN
                v_diff := NEW.quantity_required - OLD.quantity_required;
                IF v_diff > 0 THEN
                    IF (SELECT quantity_in_stock FROM pharmaceutical_items
                        WHERE drug_number = NEW.drug_number) < v_diff THEN
                        RAISE EXCEPTION
                            'Insufficient stock to increase quantity for drug %. Available: %, Extra needed: %',
                            NEW.drug_number,
                            (SELECT quantity_in_stock FROM pharmaceutical_items WHERE drug_number = NEW.drug_number),
                            v_diff;
                    END IF;
                END IF;
                UPDATE pharmaceutical_items
                SET quantity_in_stock = quantity_in_stock - v_diff
                WHERE drug_number = NEW.drug_number;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_drug_requisition_update
            BEFORE UPDATE ON requisition_drug_items
            FOR EACH ROW EXECUTE FUNCTION trg_adjust_drug_stock_on_update();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_adjust_supply_stock_on_update()
            RETURNS TRIGGER AS \$\$
            DECLARE v_diff INT;
            BEGIN
                v_diff := NEW.quantity_required - OLD.quantity_required;
                IF v_diff > 0 THEN
                    IF (SELECT quantity_in_stock FROM supply_items
                        WHERE item_number = NEW.item_number) < v_diff THEN
                        RAISE EXCEPTION
                            'Insufficient stock to increase quantity for supply item %. Available: %, Extra needed: %',
                            NEW.item_number,
                            (SELECT quantity_in_stock FROM supply_items WHERE item_number = NEW.item_number),
                            v_diff;
                    END IF;
                END IF;
                UPDATE supply_items
                SET quantity_in_stock = quantity_in_stock - v_diff
                WHERE item_number = NEW.item_number;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_supply_requisition_update
            BEFORE UPDATE ON requisition_supply_items
            FOR EACH ROW EXECUTE FUNCTION trg_adjust_supply_stock_on_update();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_check_drug_reorder()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.quantity_in_stock <= NEW.reorder_level THEN
                    RAISE NOTICE 'REORDER ALERT: Drug \"%\" (ID: %) — Stock: %, Reorder at: %',
                        NEW.drug_name, NEW.drug_number,
                        NEW.quantity_in_stock, NEW.reorder_level;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_drug_stock_update
            AFTER UPDATE OF quantity_in_stock ON pharmaceutical_items
            FOR EACH ROW EXECUTE FUNCTION trg_check_drug_reorder();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_check_supply_reorder()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.quantity_in_stock <= NEW.reorder_level THEN
                    RAISE NOTICE 'REORDER ALERT: Supply item \"%\" (ID: %) — Stock: %, Reorder at: %',
                        NEW.item_name, NEW.item_number,
                        NEW.quantity_in_stock, NEW.reorder_level;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_supply_stock_update
            AFTER UPDATE OF quantity_in_stock ON supply_items
            FOR EACH ROW EXECUTE FUNCTION trg_check_supply_reorder();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_protect_supplier()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_drug_count   INT;
                v_supply_count INT;
            BEGIN
                SELECT COUNT(*) INTO v_drug_count
                FROM pharmaceutical_items WHERE supplier_number = OLD.supplier_number;

                SELECT COUNT(*) INTO v_supply_count
                FROM supply_items WHERE supplier_number = OLD.supplier_number;

                IF v_drug_count > 0 OR v_supply_count > 0 THEN
                    RAISE EXCEPTION
                        'Cannot delete supplier %. They supply % drug(s) and % supply item(s).',
                        OLD.supplier_name, v_drug_count, v_supply_count;
                END IF;
                RETURN OLD;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER before_supplier_delete
            BEFORE DELETE ON suppliers
            FOR EACH ROW EXECUTE FUNCTION trg_protect_supplier();
        ");

        // ── Procedures ─────────────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE restock_item(
                p_item_type VARCHAR,
                p_item_id   INT,
                p_quantity  INT
            )
            LANGUAGE plpgsql AS \$\$
            BEGIN
                IF p_quantity <= 0 THEN
                    RAISE EXCEPTION 'Restock quantity must be greater than zero.';
                END IF;

                IF LOWER(p_item_type) = 'drug' THEN
                    IF NOT EXISTS (SELECT 1 FROM pharmaceutical_items WHERE drug_number = p_item_id) THEN
                        RAISE EXCEPTION 'Drug with ID % not found.', p_item_id;
                    END IF;
                    UPDATE pharmaceutical_items
                    SET quantity_in_stock = quantity_in_stock + p_quantity
                    WHERE drug_number = p_item_id;

                ELSIF LOWER(p_item_type) = 'supply' THEN
                    IF NOT EXISTS (SELECT 1 FROM supply_items WHERE item_number = p_item_id) THEN
                        RAISE EXCEPTION 'Supply item with ID % not found.', p_item_id;
                    END IF;
                    UPDATE supply_items
                    SET quantity_in_stock = quantity_in_stock + p_quantity
                    WHERE item_number = p_item_id;
                ELSE
                    RAISE EXCEPTION 'Invalid item type. Use ''drug'' or ''supply''.';
                END IF;
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE cancel_requisition(
                p_requisition_number INT
            )
            LANGUAGE plpgsql AS \$\$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM requisitions WHERE requisition_number = p_requisition_number) THEN
                    RAISE EXCEPTION 'Requisition % does not exist.', p_requisition_number;
                END IF;

                UPDATE pharmaceutical_items pi
                SET quantity_in_stock = pi.quantity_in_stock + rdi.quantity_required
                FROM requisition_drug_items rdi
                WHERE rdi.requisition_number = p_requisition_number
                  AND rdi.drug_number = pi.drug_number;

                UPDATE supply_items si
                SET quantity_in_stock = si.quantity_in_stock + rsi.quantity_required
                FROM requisition_supply_items rsi
                WHERE rsi.requisition_number = p_requisition_number
                  AND rsi.item_number = si.item_number;

                DELETE FROM requisition_drug_items   WHERE requisition_number = p_requisition_number;
                DELETE FROM requisition_supply_items WHERE requisition_number = p_requisition_number;
                DELETE FROM requisitions             WHERE requisition_number = p_requisition_number;

                RAISE NOTICE 'Requisition % cancelled and stock restored.', p_requisition_number;
            END;
            \$\$;
        ");
    }

    public function down(): void
    {
        // Drop triggers first, then their functions, then procedures
        $triggers = [
            ['after_drug_requisition_insert',  'requisition_drug_items'],
            ['after_supply_requisition_insert', 'requisition_supply_items'],
            ['after_drug_requisition_delete',   'requisition_drug_items'],
            ['after_supply_requisition_delete', 'requisition_supply_items'],
            ['after_drug_requisition_update',   'requisition_drug_items'],
            ['after_supply_requisition_update', 'requisition_supply_items'],
            ['after_drug_stock_update',         'pharmaceutical_items'],
            ['after_supply_stock_update',       'supply_items'],
            ['before_supplier_delete',          'suppliers'],
        ];

        foreach ($triggers as [$trigger, $table]) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$trigger} ON {$table};");
        }

        $functions = [
            'trg_deduct_drug_stock', 'trg_deduct_supply_stock',
            'trg_restore_drug_stock', 'trg_restore_supply_stock',
            'trg_adjust_drug_stock_on_update', 'trg_adjust_supply_stock_on_update',
            'trg_check_drug_reorder', 'trg_check_supply_reorder',
            'trg_protect_supplier',
            'get_stock_status', 'get_requisition_total_cost',
            'get_low_stock_items', 'get_supplier_inventory_value',
            'is_drug_active_for_patient',
        ];

        foreach ($functions as $fn) {
            DB::unprepared("DROP FUNCTION IF EXISTS {$fn} CASCADE;");
        }

        DB::unprepared("DROP PROCEDURE IF EXISTS restock_item CASCADE;");
        DB::unprepared("DROP PROCEDURE IF EXISTS cancel_requisition CASCADE;");
    }
};