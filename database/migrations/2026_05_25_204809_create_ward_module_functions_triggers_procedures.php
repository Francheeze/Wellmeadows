<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Functions ──────────────────────────────────────────

        // Get bed availability status for a ward
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_ward_bed_status(
                p_ward_number INT
            ) RETURNS VARCHAR AS \$\$
            DECLARE
                v_total     INT;
                v_occupied  INT;
            BEGIN
                SELECT COUNT(*) INTO v_total
                FROM beds WHERE ward_number = p_ward_number;

                SELECT COUNT(*) INTO v_occupied
                FROM beds WHERE ward_number = p_ward_number AND status = 'occupied';

                IF v_total = 0 THEN RETURN 'NO BEDS';
                ELSIF v_occupied = v_total THEN RETURN 'FULL';
                ELSIF v_occupied >= (v_total * 0.8) THEN RETURN 'ALMOST FULL';
                ELSE RETURN 'AVAILABLE';
                END IF;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // Get total available beds in a ward
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_available_beds_count(
                p_ward_number INT
            ) RETURNS INT AS \$\$
            DECLARE
                v_count INT;
            BEGIN
                SELECT COUNT(*) INTO v_count
                FROM beds
                WHERE ward_number = p_ward_number AND status = 'available';
                RETURN v_count;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // Get total staff assigned to a ward for a given week
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_ward_staff_count(
                p_ward_number   INT,
                p_week_start    DATE
            ) RETURNS INT AS \$\$
            DECLARE
                v_count INT;
            BEGIN
                SELECT COUNT(*) INTO v_count
                FROM staff_rotas
                WHERE ward_number = p_ward_number
                  AND week_start_date = p_week_start;
                RETURN v_count;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // Check if a staff is already assigned to a ward on a given week
        DB::unprepared("
            CREATE OR REPLACE FUNCTION is_staff_already_assigned(
                p_staff_number  VARCHAR,
                p_week_start    DATE
            ) RETURNS BOOLEAN AS \$\$
            BEGIN
                RETURN EXISTS (
                    SELECT 1 FROM staff_rotas
                    WHERE staff_number = p_staff_number
                      AND week_start_date = p_week_start
                );
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // ── Trigger Functions ───────────────────────────────────

        // Prevent duplicate staff assignment in same week
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_prevent_duplicate_rota()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF EXISTS (
                    SELECT 1 FROM staff_rotas
                    WHERE staff_number = NEW.staff_number
                      AND week_start_date = NEW.week_start_date
                      AND id != COALESCE(NEW.id, -1)
                ) THEN
                    RAISE EXCEPTION
                        'Staff % is already assigned to a ward for the week of %.',
                        NEW.staff_number, NEW.week_start_date;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER before_rota_insert_update
            BEFORE INSERT OR UPDATE ON staff_rotas
            FOR EACH ROW EXECUTE FUNCTION trg_prevent_duplicate_rota();
        ");

        // Auto update ward total_beds when bed is added
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_update_ward_total_beds_on_insert()
            RETURNS TRIGGER AS \$\$
            BEGIN
                UPDATE wards
                SET total_beds = (
                    SELECT COUNT(*) FROM beds WHERE ward_number = NEW.ward_number
                )
                WHERE ward_number = NEW.ward_number;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_bed_insert
            AFTER INSERT ON beds
            FOR EACH ROW EXECUTE FUNCTION trg_update_ward_total_beds_on_insert();
        ");

        // Auto update ward total_beds when bed is deleted
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_update_ward_total_beds_on_delete()
            RETURNS TRIGGER AS \$\$
            BEGIN
                UPDATE wards
                SET total_beds = (
                    SELECT COUNT(*) FROM beds WHERE ward_number = OLD.ward_number
                )
                WHERE ward_number = OLD.ward_number;
                RETURN OLD;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_bed_delete
            AFTER DELETE ON beds
            FOR EACH ROW EXECUTE FUNCTION trg_update_ward_total_beds_on_delete();
        ");

        // Prevent deleting a ward that still has beds
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_protect_ward_with_beds()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_bed_count INT;
            BEGIN
                SELECT COUNT(*) INTO v_bed_count
                FROM beds WHERE ward_number = OLD.ward_number;

                IF v_bed_count > 0 THEN
                    RAISE EXCEPTION
                        'Cannot delete ward %. It still has % bed(s) assigned.',
                        OLD.ward_number, v_bed_count;
                END IF;
                RETURN OLD;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER before_ward_delete
            BEFORE DELETE ON wards
            FOR EACH ROW EXECUTE FUNCTION trg_protect_ward_with_beds();
        ");

        // ── Procedures ─────────────────────────────────────────

        // Assign staff to a ward rota
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE assign_staff_to_ward(
                p_ward_number   INT,
                p_staff_number  VARCHAR,
                p_shift         VARCHAR,
                p_week_start    DATE
            )
            LANGUAGE plpgsql AS \$\$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM wards WHERE ward_number = p_ward_number) THEN
                    RAISE EXCEPTION 'Ward % does not exist.', p_ward_number;
                END IF;

                IF NOT EXISTS (SELECT 1 FROM staff WHERE staff_number = p_staff_number) THEN
                    RAISE EXCEPTION 'Staff % does not exist.', p_staff_number;
                END IF;

                IF EXISTS (
                    SELECT 1 FROM staff_rotas
                    WHERE staff_number = p_staff_number
                      AND week_start_date = p_week_start
                ) THEN
                    RAISE EXCEPTION 'Staff % is already assigned for the week of %.', p_staff_number, p_week_start;
                END IF;

                INSERT INTO staff_rotas (ward_number, staff_number, shift, week_start_date, created_at, updated_at)
                VALUES (p_ward_number, p_staff_number, p_shift, p_week_start, NOW(), NOW());

                RAISE NOTICE 'Staff % assigned to Ward % on % shift for week of %.',
                    p_staff_number, p_ward_number, p_shift, p_week_start;
            END;
            \$\$;
        ");

        // Remove all staff from a ward for a given week
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE clear_ward_rota(
                p_ward_number   INT,
                p_week_start    DATE
            )
            LANGUAGE plpgsql AS \$\$
            DECLARE
                v_count INT;
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM wards WHERE ward_number = p_ward_number) THEN
                    RAISE EXCEPTION 'Ward % does not exist.', p_ward_number;
                END IF;

                SELECT COUNT(*) INTO v_count
                FROM staff_rotas
                WHERE ward_number = p_ward_number
                  AND week_start_date = p_week_start;

                DELETE FROM staff_rotas
                WHERE ward_number = p_ward_number
                  AND week_start_date = p_week_start;

                RAISE NOTICE '% staff removed from Ward % rota for week of %.',
                    v_count, p_ward_number, p_week_start;
            END;
            \$\$;
        ");

        // Update all beds in a ward to available
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE reset_ward_beds(
                p_ward_number INT
            )
            LANGUAGE plpgsql AS \$\$
            DECLARE
                v_count INT;
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM wards WHERE ward_number = p_ward_number) THEN
                    RAISE EXCEPTION 'Ward % does not exist.', p_ward_number;
                END IF;

                SELECT COUNT(*) INTO v_count
                FROM beds WHERE ward_number = p_ward_number;

                UPDATE beds SET status = 'available'
                WHERE ward_number = p_ward_number;

                RAISE NOTICE '% beds in Ward % reset to available.', v_count, p_ward_number;
            END;
            \$\$;
        ");
    }

    public function down(): void
    {
        // Drop triggers first
        $triggers = [
            ['before_rota_insert_update', 'staff_rotas'],
            ['after_bed_insert',          'beds'],
            ['after_bed_delete',          'beds'],
            ['before_ward_delete',        'wards'],
        ];

        foreach ($triggers as [$trigger, $table]) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$trigger} ON {$table};");
        }

        // Drop functions
        $functions = [
            'trg_prevent_duplicate_rota',
            'trg_update_ward_total_beds_on_insert',
            'trg_update_ward_total_beds_on_delete',
            'trg_protect_ward_with_beds',
            'get_ward_bed_status',
            'get_available_beds_count',
            'get_ward_staff_count',
            'is_staff_already_assigned',
        ];

        foreach ($functions as $fn) {
            DB::unprepared("DROP FUNCTION IF EXISTS {$fn} CASCADE;");
        }

        // Drop procedures
        DB::unprepared("DROP PROCEDURE IF EXISTS assign_staff_to_ward CASCADE;");
        DB::unprepared("DROP PROCEDURE IF EXISTS clear_ward_rota CASCADE;");
        DB::unprepared("DROP PROCEDURE IF EXISTS reset_ward_beds CASCADE;");
    }
};