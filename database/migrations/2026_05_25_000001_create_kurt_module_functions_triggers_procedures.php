<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Functions ──────────────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE FUNCTION count_staff_qualifications(
                p_staff_number VARCHAR
            ) RETURNS INT AS \$\$
            BEGIN
                RETURN (SELECT COUNT(*) FROM qualifications
                        WHERE staff_number = p_staff_number);
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION count_staff_work_experience(
                p_staff_number VARCHAR
            ) RETURNS INT AS \$\$
            BEGIN
                RETURN (SELECT COUNT(*) FROM work_experiences
                        WHERE staff_number = p_staff_number);
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION calculate_staff_experience_years(
                p_staff_number VARCHAR
            ) RETURNS NUMERIC AS \$\$
            DECLARE
                v_total_years NUMERIC := 0;
                work_rec RECORD;
            BEGIN
                FOR work_rec IN SELECT start_date, finish_date FROM work_experiences
                               WHERE staff_number = p_staff_number LOOP
                    IF work_rec.finish_date IS NULL THEN
                        v_total_years := v_total_years + 
                            EXTRACT(YEAR FROM AGE(CURRENT_DATE, work_rec.start_date));
                    ELSE
                        v_total_years := v_total_years + 
                            EXTRACT(YEAR FROM AGE(work_rec.finish_date, work_rec.start_date));
                    END IF;
                END LOOP;
                RETURN v_total_years;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // ── Trigger Functions ───────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_log_qualification_add()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'activity_logs') THEN
                    CREATE TABLE activity_logs (
                        id SERIAL PRIMARY KEY,
                        staff_number VARCHAR(10),
                        action VARCHAR(100),
                        details TEXT,
                        created_at TIMESTAMP
                    );
                END IF;
                
                INSERT INTO activity_logs (staff_number, action, details, created_at)
                VALUES (NEW.staff_number, 'QUALIFICATION_ADDED', 
                        CONCAT('Added qualification: ', NEW.type, ' at ', NEW.institution), 
                        NOW());
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER after_qualification_insert
            AFTER INSERT ON qualifications
            FOR EACH ROW EXECUTE FUNCTION trg_log_qualification_add();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_validate_work_dates()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.start_date >= NEW.finish_date AND NEW.finish_date IS NOT NULL THEN
                    RAISE EXCEPTION 'Start date must be before finish date for work experience';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER before_work_experience_insert
            BEFORE INSERT ON work_experiences
            FOR EACH ROW EXECUTE FUNCTION trg_validate_work_dates();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_prevent_duplicate_qualification()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF EXISTS (SELECT 1 FROM qualifications 
                          WHERE staff_number = NEW.staff_number 
                            AND type = NEW.type 
                            AND institution = NEW.institution) THEN
                    RAISE EXCEPTION 'This qualification already exists for this staff member';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER prevent_duplicate_qualification
            BEFORE INSERT ON qualifications
            FOR EACH ROW EXECUTE FUNCTION trg_prevent_duplicate_qualification();
        ");

        // ── Stored Procedures ──────────────────────────────────

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE add_staff_qualification(
                p_staff_number VARCHAR,
                p_type VARCHAR,
                p_date DATE,
                p_institution VARCHAR
            ) LANGUAGE plpgsql
            AS \$\$
            BEGIN
                INSERT INTO qualifications (staff_number, type, date, institution, created_at, updated_at)
                VALUES (p_staff_number, p_type, p_date, p_institution, NOW(), NOW());
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE add_work_experience(
                p_staff_number VARCHAR,
                p_position VARCHAR,
                p_organization VARCHAR,
                p_start_date DATE,
                p_finish_date DATE
            ) LANGUAGE plpgsql
            AS \$\$
            BEGIN
                INSERT INTO work_experiences (staff_number, position, organization, start_date, finish_date, created_at, updated_at)
                VALUES (p_staff_number, p_position, p_organization, p_start_date, p_finish_date, NOW(), NOW());
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE delete_staff_history(
                p_staff_number VARCHAR
            ) LANGUAGE plpgsql
            AS \$\$
            BEGIN
                DELETE FROM qualifications WHERE staff_number = p_staff_number;
                DELETE FROM work_experiences WHERE staff_number = p_staff_number;
            END;
            \$\$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers first
        DB::unprepared("DROP TRIGGER IF EXISTS after_qualification_insert ON qualifications;");
        DB::unprepared("DROP TRIGGER IF EXISTS before_work_experience_insert ON work_experiences;");
        DB::unprepared("DROP TRIGGER IF EXISTS prevent_duplicate_qualification ON qualifications;");
        
        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS count_staff_qualifications;");
        DB::unprepared("DROP FUNCTION IF EXISTS count_staff_work_experience;");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_staff_experience_years;");
        
        // Drop procedures
        DB::unprepared("DROP PROCEDURE IF EXISTS add_staff_qualification;");
        DB::unprepared("DROP PROCEDURE IF EXISTS add_work_experience;");
        DB::unprepared("DROP PROCEDURE IF EXISTS delete_staff_history;");
    }
};