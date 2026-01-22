-- ============================================
-- ALLERGY TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[ALLERGY]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_ALLERGY_SEVERITY')
BEGIN
    ALTER TABLE [ALLERGY]
    ADD CONSTRAINT CK_ALLERGY_SEVERITY 
    CHECK (severity IN ('Mild', 'Moderate', 'Severe'));
END;
GO

-- ============================================
-- CONDITION TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[CONDITION_AT_BIRTH]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_CONDITION_STATUS')
BEGIN
    ALTER TABLE [CONDITION_AT_BIRTH]
    ADD CONSTRAINT CK_CONDITION_STATUS 
    CHECK (status IN ('Active', 'Inactive', 'Resolved'));
END;
GO

-- ============================================
-- VITAL SIGN TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[VITAL_SIGN]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_VITAL_VALUE_RANGE')
BEGIN
    ALTER TABLE [VITAL_SIGN]
    ADD CONSTRAINT CK_VITAL_VALUE_RANGE 
    CHECK (value >= 0 AND value <= 1000);
END;
GO

IF OBJECT_ID('[VITAL_SIGN]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_VITAL_SYSTOLIC_BP')
BEGIN
    ALTER TABLE [VITAL_SIGN]
    ADD CONSTRAINT CK_VITAL_SYSTOLIC_BP 
    CHECK (SystolicBP IS NULL OR (SystolicBP >= 0 AND SystolicBP <= 300));
END;
GO

IF OBJECT_ID('[VITAL_SIGN]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_VITAL_DIASTOLIC_BP')
BEGIN
    ALTER TABLE [VITAL_SIGN]
    ADD CONSTRAINT CK_VITAL_DIASTOLIC_BP 
    CHECK (DiastolicBP IS NULL OR (DiastolicBP >= 0 AND DiastolicBP <= 200));
END;
GO

-- ============================================
-- DIAGNOSIS TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[DIAGNOSIS]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_DIAGNOSIS_STATUS')
BEGIN
    ALTER TABLE [DIAGNOSIS]
    ADD CONSTRAINT CK_DIAGNOSIS_STATUS 
    CHECK (status IN ('Active', 'Resolved', 'Under Review'));
END;
GO

-- ============================================
-- NOTE TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[NOTE]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_NOTE_PRIORITY')
BEGIN
    ALTER TABLE [NOTE]
    ADD CONSTRAINT CK_NOTE_PRIORITY 
    CHECK (note_priority IN ('Low', 'Medium', 'High'));
END;
GO

-- ============================================
-- MEDICATION TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[MEDICATION]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_MEDICATION_QUANTITY')
BEGIN
    ALTER TABLE [MEDICATION]
    ADD CONSTRAINT CK_MEDICATION_QUANTITY 
    CHECK (quantity >= 0);
END;
GO

IF OBJECT_ID('[MEDICATION]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_MEDICATION_STATUS')
BEGIN
    ALTER TABLE [MEDICATION]
    ADD CONSTRAINT CK_MEDICATION_STATUS 
    CHECK (statuses IN ('Available', 'Out of Stock', 'Expired', 'Discontinued'));
END;
GO

-- ============================================
-- PATIENT TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[PATIENT]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_PATIENT_STATUS')
BEGIN
    ALTER TABLE [PATIENT]
    ADD CONSTRAINT CK_PATIENT_STATUS 
    CHECK (patient_status IN ('Active', 'Discharged', 'Deceased', 'Pending'));
END;
GO

-- ============================================
-- MEDICATION SCHEDULE TABLE CONSTRAINTS
-- ============================================
IF OBJECT_ID('[MEDICATION_SCHEDULE]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.check_constraints WHERE name = 'CK_MED_SCHEDULE_STATUS')
BEGIN
    ALTER TABLE [MEDICATION_SCHEDULE]
    ADD CONSTRAINT CK_MED_SCHEDULE_STATUS 
    CHECK (schedule_status IN ('Scheduled', 'Administered', 'Skipped', 'Pending'));
END;
GO

-- ============================================
-- ADD INDEX FOR PERFORMANCE
-- ============================================
IF OBJECT_ID('[ALLERGY]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_ALLERGY_PATIENT' AND object_id = OBJECT_ID('[ALLERGY]'))
BEGIN
    CREATE INDEX IDX_ALLERGY_PATIENT ON [ALLERGY](PatientID);
END;
GO

IF OBJECT_ID('[CONDITION_AT_BIRTH]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_CONDITION_PATIENT' AND object_id = OBJECT_ID('[CONDITION_AT_BIRTH]'))
BEGIN
    CREATE INDEX IDX_CONDITION_PATIENT ON [CONDITION_AT_BIRTH](PatientID);
END;
GO

IF OBJECT_ID('[VITAL_SIGN]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_VITAL_SIGN_PATIENT' AND object_id = OBJECT_ID('[VITAL_SIGN]'))
BEGIN
    CREATE INDEX IDX_VITAL_SIGN_PATIENT ON [VITAL_SIGN](PatientID);
END;
GO

IF OBJECT_ID('[DIAGNOSIS]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_DIAGNOSIS_CHART' AND object_id = OBJECT_ID('[DIAGNOSIS]'))
BEGIN
    CREATE INDEX IDX_DIAGNOSIS_CHART ON [DIAGNOSIS](ChartID);
END;
GO

IF OBJECT_ID('[NOTE]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_NOTE_CHART' AND object_id = OBJECT_ID('[NOTE]'))
BEGIN
    CREATE INDEX IDX_NOTE_CHART ON [NOTE](ChartID);
END;
GO

IF OBJECT_ID('[MEDICATION]', 'U') IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'IDX_MEDICATION_PATIENT' AND object_id = OBJECT_ID('[MEDICATION]'))
BEGIN
    CREATE INDEX IDX_MEDICATION_PATIENT ON [MEDICATION](PatientID);
END;
GO

PRINT 'Database constraints and indexes added successfully!';
GO
