<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Order matters — dependencies must be created first:
     *   1. Functions  (summary_view calls the 3 basic functions)
     *   2. Procedures (get_patient_summary calls the 3 basic functions)
     *   3. Trigger functions + their triggers
     */
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════
        // FUNCTIONS
        // ═══════════════════════════════════════════════════════

        // -------------------------------------------------------
        // FUNCTION 1: get_patient_full_name
        // Returns the concatenated full name of a patient.
        // Usage: SELECT get_patient_full_name('P1001');
        // FIX: parameter changed from INT to VARCHAR to match
        //      the patients.patient_number column type.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_patient_full_name(p_patient_number VARCHAR)
            RETURNS VARCHAR
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_full_name VARCHAR;
            BEGIN
                SELECT first_name || ' ' || last_name
                INTO   v_full_name
                FROM   patients
                WHERE  patient_number = p_patient_number;

                IF NOT FOUND THEN
                    RETURN 'Patient not found';
                END IF;

                RETURN v_full_name;
            END;
            \$\$;
        ");

        // -------------------------------------------------------
        // FUNCTION 2: get_patient_age
        // Calculates the current age in years from date_of_birth.
        // Returns -1 if the patient does not exist.
        // Usage: SELECT get_patient_age('P1001');
        // FIX: parameter changed from INT to VARCHAR.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_patient_age(p_patient_number VARCHAR)
            RETURNS INT
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_dob DATE;
                v_age INT;
            BEGIN
                SELECT date_of_birth
                INTO   v_dob
                FROM   patients
                WHERE  patient_number = p_patient_number;

                IF NOT FOUND THEN
                    RETURN -1;
                END IF;

                v_age := DATE_PART('year', AGE(CURRENT_DATE, v_dob))::INT;

                RETURN v_age;
            END;
            \$\$;
        ");

        // -------------------------------------------------------
        // FUNCTION 3: count_patient_appointments
        // Returns the total appointment count for a patient.
        // Usage: SELECT count_patient_appointments('P1001');
        // FIX: parameter changed from INT to VARCHAR.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION count_patient_appointments(p_patient_number VARCHAR)
            RETURNS INT
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_count INT := 0;
            BEGIN
                SELECT COUNT(*)
                INTO   v_count
                FROM   appointments
                WHERE  patient_number = p_patient_number;

                RETURN v_count;
            END;
            \$\$;
        ");

        // -------------------------------------------------------
        // FUNCTION 4: get_patient_summary_view
        // Returns a full patient profile as a table result.
        // Calls the 3 functions above internally.
        // Usage: SELECT * FROM get_patient_summary_view('P1001');
        // FIX: parameter and return column changed from INT to VARCHAR.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_patient_summary_view(p_patient_number VARCHAR)
            RETURNS TABLE (
                patient_number      VARCHAR,
                full_name           TEXT,
                address             TEXT,
                telephone_number    VARCHAR,
                date_of_birth       DATE,
                age                 INT,
                sex                 VARCHAR,
                marital_status      VARCHAR,
                date_registered     DATE,
                referred_by_clinic  VARCHAR,
                total_appointments  INT,
                currently_admitted  TEXT
            )
            LANGUAGE plpgsql
            AS \$\$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM patients WHERE patients.patient_number = p_patient_number
                ) THEN
                    RAISE EXCEPTION 'Patient with number % not found.', p_patient_number
                        USING ERRCODE = 'P0002';
                END IF;

                RETURN QUERY
                SELECT
                    p.patient_number,
                    (p.first_name || ' ' || p.last_name)::TEXT                        AS full_name,
                    p.address::TEXT,
                    p.telephone_number,
                    p.date_of_birth,
                    DATE_PART('year', AGE(CURRENT_DATE, p.date_of_birth))::INT         AS age,
                    p.sex,
                    p.marital_status,
                    p.date_registered,
                    ld.full_name::VARCHAR                                              AS referred_by_clinic,
                    count_patient_appointments(p.patient_number)                       AS total_appointments,
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM in_patients ip
                            WHERE  ip.patient_number = p.patient_number
                              AND  ip.actual_leave IS NULL
                        ) THEN 'Yes'
                        ELSE 'No'
                    END                                                               AS currently_admitted
                FROM   patients p
                LEFT JOIN local_doctors ld ON ld.clinic_number = p.reffered_by
                WHERE  p.patient_number = p_patient_number;
            END;
            \$\$;
        ");

        // ═══════════════════════════════════════════════════════
        // PROCEDURES
        // ═══════════════════════════════════════════════════════

        // -------------------------------------------------------
        // PROCEDURE 1: register_patient
        // Validates required fields and inserts a new patient.
        // Usage:
        //   DB::statement("CALL register_patient(
        //       'Juan', 'Dela Cruz', '123 Rizal St', '09171234567',
        //       '1990-05-15', 'Male', 'Single', 2
        //   )");
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE register_patient(
                p_first_name      VARCHAR,
                p_last_name       VARCHAR,
                p_address         TEXT,
                p_telephone       VARCHAR,
                p_dob             DATE,
                p_sex             VARCHAR,
                p_marital_status  VARCHAR,
                p_referred_by     INT
            )
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_new_patient_number INT;
            BEGIN
                IF p_first_name IS NULL OR TRIM(p_first_name) = '' THEN
                    RAISE EXCEPTION 'First name is required.'
                        USING ERRCODE = 'P0001';
                END IF;

                IF p_last_name IS NULL OR TRIM(p_last_name) = '' THEN
                    RAISE EXCEPTION 'Last name is required.'
                        USING ERRCODE = 'P0001';
                END IF;

                IF p_dob IS NULL OR p_dob >= CURRENT_DATE THEN
                    RAISE EXCEPTION 'A valid date of birth is required.'
                        USING ERRCODE = 'P0001';
                END IF;

                INSERT INTO patients (
                    first_name,
                    last_name,
                    address,
                    telephone_number,
                    date_of_birth,
                    sex,
                    marital_status,
                    date_registered,
                    reffered_by,
                    created_at,
                    update_at
                )
                VALUES (
                    p_first_name,
                    p_last_name,
                    p_address,
                    p_telephone,
                    p_dob,
                    p_sex,
                    p_marital_status,
                    CURRENT_DATE,
                    p_referred_by,
                    NOW(),
                    NOW()
                )
                RETURNING patient_number INTO v_new_patient_number;

                RAISE NOTICE 'Patient registered successfully. New patient_number: %', v_new_patient_number;
            END;
            \$\$;
        ");

        // -------------------------------------------------------
        // PROCEDURE 2: get_patient_summary
        // Prints a patient's profile info via RAISE NOTICE.
        // Calls get_patient_full_name, get_patient_age,
        // and count_patient_appointments internally.
        // Usage: DB::statement("CALL get_patient_summary('P1001')");
        // FIX: parameter changed from INT to VARCHAR.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE get_patient_summary(p_patient_number VARCHAR)
            LANGUAGE plpgsql
            AS \$\$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM patients WHERE patient_number = p_patient_number
                ) THEN
                    RAISE EXCEPTION 'Patient with number % not found.', p_patient_number
                        USING ERRCODE = 'P0002';
                END IF;

                RAISE NOTICE 'Patient #: %',          p_patient_number;
                RAISE NOTICE 'Full name: %',          get_patient_full_name(p_patient_number);
                RAISE NOTICE 'Age: %',                get_patient_age(p_patient_number);
                RAISE NOTICE 'Total appointments: %', count_patient_appointments(p_patient_number);
            END;
            \$\$;
        ");

        // -------------------------------------------------------
        // PROCEDURE 3: update_patient_contact
        // Updates address and/or telephone for a patient.
        // Pass NULL to skip updating a specific field.
        // Usage:
        //   DB::statement("CALL update_patient_contact('P1001', '456 Quezon Ave', '09987654321')");
        //   DB::statement("CALL update_patient_contact('P1001', NULL, '09987654321')");
        // FIX: parameter changed from INT to VARCHAR.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE update_patient_contact(
                p_patient_number  VARCHAR,
                p_new_address     TEXT,
                p_new_telephone   VARCHAR
            )
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_rows_affected INT;
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM patients WHERE patient_number = p_patient_number
                ) THEN
                    RAISE EXCEPTION 'Patient with number % not found. Update aborted.', p_patient_number
                        USING ERRCODE = 'P0002';
                END IF;

                IF (p_new_address IS NULL OR TRIM(p_new_address) = '')
                AND (p_new_telephone IS NULL OR TRIM(p_new_telephone) = '') THEN
                    RAISE EXCEPTION 'Provide at least one field to update (address or telephone).'
                        USING ERRCODE = 'P0001';
                END IF;

                UPDATE patients
                SET
                    address          = COALESCE(NULLIF(TRIM(p_new_address),   ''), address),
                    telephone_number = COALESCE(NULLIF(TRIM(p_new_telephone), ''), telephone_number),
                    update_at        = NOW()
                WHERE  patient_number = p_patient_number;

                GET DIAGNOSTICS v_rows_affected = ROW_COUNT;

                RAISE NOTICE 'Contact info updated. Rows affected: %', v_rows_affected;
            END;
            \$\$;
        ");

        // ═══════════════════════════════════════════════════════
        // TRIGGER FUNCTIONS + TRIGGERS
        // ═══════════════════════════════════════════════════════

        // -------------------------------------------------------
        // TRIGGER 1: trg_patients_set_registered_date
        // BEFORE INSERT on patients
        // Auto-fills date_registered, created_at, and update_at
        // if they are left NULL when inserting a new patient.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_patients_set_registered_date()
            RETURNS TRIGGER
            LANGUAGE plpgsql
            AS \$\$
            BEGIN
                IF NEW.date_registered IS NULL THEN
                    NEW.date_registered := CURRENT_DATE;
                END IF;

                IF NEW.created_at IS NULL THEN
                    NEW.created_at := NOW();
                END IF;

                NEW.update_at := NOW();

                RETURN NEW;
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_patients_set_registered_date
            BEFORE INSERT ON patients
            FOR EACH ROW
            EXECUTE FUNCTION fn_patients_set_registered_date();
        ");

        // -------------------------------------------------------
        // TRIGGER 2: trg_patients_stamp_updated_at
        // BEFORE UPDATE on patients
        // Automatically refreshes update_at to NOW() every time
        // any column in the patients row is modified.
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_patients_stamp_updated_at()
            RETURNS TRIGGER
            LANGUAGE plpgsql
            AS \$\$
            BEGIN
                NEW.update_at := NOW();
                RETURN NEW;
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_patients_stamp_updated_at
            BEFORE UPDATE ON patients
            FOR EACH ROW
            EXECUTE FUNCTION fn_patients_stamp_updated_at();
        ");

        // -------------------------------------------------------
        // TRIGGER 3: trg_prevent_duplicate_active_inpatient
        // BEFORE INSERT on in_patients
        // Blocks the insert if the patient is already admitted
        // (i.e. a row exists with actual_leave IS NULL).
        // -------------------------------------------------------
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_prevent_duplicate_active_inpatient()
            RETURNS TRIGGER
            LANGUAGE plpgsql
            AS \$\$
            DECLARE
                v_active_count INT := 0;
            BEGIN
                SELECT COUNT(*)
                INTO   v_active_count
                FROM   in_patients
                WHERE  patient_number = NEW.patient_number
                  AND  actual_leave   IS NULL;

                IF v_active_count > 0 THEN
                    RAISE EXCEPTION
                        'Patient % is already admitted. Discharge them before a new admission.',
                        NEW.patient_number
                        USING ERRCODE = 'P0003';
                END IF;

                RETURN NEW;
            END;
            \$\$;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_prevent_duplicate_active_inpatient
            BEFORE INSERT ON in_patients
            FOR EACH ROW
            EXECUTE FUNCTION fn_prevent_duplicate_active_inpatient();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * Order matters — drop triggers before their functions,
     * and drop dependent objects before their dependencies.
     */
    public function down(): void
    {
        // ── Triggers first (depend on trigger functions) ──────
        DB::unprepared('DROP TRIGGER IF EXISTS trg_patients_set_registered_date ON patients;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_patients_stamp_updated_at ON patients;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_duplicate_active_inpatient ON in_patients;');

        // ── Trigger functions ──────────────────────────────────
        DB::unprepared('DROP FUNCTION IF EXISTS fn_patients_set_registered_date();');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_patients_stamp_updated_at();');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_prevent_duplicate_active_inpatient();');

        // ── Procedures ────────────────────────────────────────
        DB::unprepared('DROP PROCEDURE IF EXISTS register_patient(VARCHAR, VARCHAR, TEXT, VARCHAR, DATE, VARCHAR, VARCHAR, INT);');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_patient_summary(VARCHAR);');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_patient_contact(VARCHAR, TEXT, VARCHAR);');

        // ── Functions — dependents first ──────────────────────
        // get_patient_summary_view calls the 3 basic functions,
        // so it must be dropped before them.
        DB::unprepared('DROP FUNCTION IF EXISTS get_patient_summary_view(VARCHAR);');
        DB::unprepared('DROP FUNCTION IF EXISTS get_patient_full_name(VARCHAR);');
        DB::unprepared('DROP FUNCTION IF EXISTS get_patient_age(VARCHAR);');
        DB::unprepared('DROP FUNCTION IF EXISTS count_patient_appointments(VARCHAR);');
    }
};