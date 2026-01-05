IF OBJECT_ID('sp_setup_schema', 'P') IS NOT NULL
    DROP PROCEDURE sp_setup_schema;
GO

CREATE PROCEDURE sp_setup_schema
AS
BEGIN
    SET NOCOUNT ON;

    /* ---------- CORE ---------- */
    EXEC sp_create_user;
    EXEC sp_create_ward;

    /* ---------- SUBTYPES ---------- */
    EXEC sp_create_nurse;
    EXEC sp_create_admin;

    /* ---------- PATIENT DOMAIN ---------- */
    EXEC sp_create_patient;
    EXEC sp_create_inpatient;
    EXEC sp_create_outpatient;
    EXEC sp_create_allergy;
    EXEC sp_create_condition_at_birth;
    EXEC sp_create_inventory;

    /* ---------- CHART DOMAIN ---------- */
    EXEC sp_create_chart;
    EXEC sp_create_medication;
    EXEC sp_create_diagnosis;
    EXEC sp_create_vital_sign;
    EXEC sp_create_procedure;
END;
GO
