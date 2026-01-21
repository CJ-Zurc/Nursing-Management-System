-- =====================================================
-- NURSING MANAGEMENT SYSTEM - DATABASE SETUP
-- Version 2.0 - January 14, 2026
-- =====================================================
-- IMPORTANT: Run this ENTIRE script in SQL Server Management Studio
-- This creates all tables and stored procedures needed
-- =====================================================

-- Step 1: Create Database (if not already created)
-- Uncomment and modify if needed:
-- CREATE DATABASE nursing_db;
-- GO

-- Step 2: Use the database
USE nursing_db;
GO

-- Step 3: Drop existing procedures (optional, for clean reinstall)
-- Uncomment if you want to start fresh:
/*
IF OBJECT_ID('sp_setup_schema', 'P') IS NOT NULL
    DROP PROCEDURE sp_setup_schema;
GO
*/

-- =====================================================
-- CORE PROCEDURES - User & Ward Management
-- =====================================================

IF OBJECT_ID('sp_create_user', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_user;
GO

CREATE PROCEDURE sp_create_user
AS
BEGIN
    IF OBJECT_ID('[USER]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [USER] (
        userID INT IDENTITY(1,1) PRIMARY KEY,
        email NVARCHAR(100) UNIQUE NOT NULL,
        password NVARCHAR(255) NOT NULL,
        first_name NVARCHAR(50) NOT NULL,
        last_name NVARCHAR(50) NOT NULL,
        role NVARCHAR(20) NOT NULL,
        contact_number NVARCHAR(11),
        created_at DATETIME DEFAULT GETDATE()
    );
END;
GO

IF OBJECT_ID('sp_create_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_ward;
GO

CREATE PROCEDURE sp_create_ward
AS
BEGIN
    IF OBJECT_ID('[WARD]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [WARD] (
        WardID INT IDENTITY(1,1) PRIMARY KEY,
        WardName NVARCHAR(100) NOT NULL,
        Capacity INT,
        FloorNumber INT,
        WardType NVARCHAR(50)
    );
END;
GO

-- =====================================================
-- NURSE & ADMIN SUBTYPES
-- =====================================================

IF OBJECT_ID('sp_create_nurse', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_nurse;
GO

CREATE PROCEDURE sp_create_nurse
AS
BEGIN
    IF OBJECT_ID('[NURSE]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [NURSE] (
        userID INT PRIMARY KEY,
        license_number NVARCHAR(50),
        WardID INT,
        nurseActive BIT DEFAULT 1,
        CONSTRAINT FK_Nurse_User FOREIGN KEY (userID) REFERENCES [USER](userID),
        CONSTRAINT FK_Nurse_Ward FOREIGN KEY (WardID) REFERENCES [WARD](WardID)
    );
END;
GO

IF OBJECT_ID('sp_create_admin', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_admin;
GO

CREATE PROCEDURE sp_create_admin
AS
BEGIN
    IF OBJECT_ID('[ADMIN]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [ADMIN] (
        userID INT PRIMARY KEY,
        adminActive BIT DEFAULT 1,
        CONSTRAINT FK_Admin_User FOREIGN KEY (userID) REFERENCES [USER](userID)
    );
END;
GO

-- =====================================================
-- PATIENT MANAGEMENT
-- =====================================================

IF OBJECT_ID('sp_create_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_patient;
GO

CREATE PROCEDURE sp_create_patient
AS
BEGIN
    IF OBJECT_ID('[PATIENT]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [PATIENT] (
        PatientID INT IDENTITY(1,1) PRIMARY KEY,
        WardID INT NOT NULL,
        first_name NVARCHAR(50) NOT NULL,
        last_name NVARCHAR(50) NOT NULL,
        dateOfBirth DATE NOT NULL,
        Sex NVARCHAR(20),
        contact_Number NVARCHAR(11),
        Guardian NVARCHAR(100),
        guardian_Number NVARCHAR(11),
        address NVARCHAR(255),
        height DECIMAL(5,2),
        weight DECIMAL(5,2),
        blood_type NVARCHAR(5),
        roomNumber NVARCHAR(10),
        bedNumber NVARCHAR(10),
        patient_status NVARCHAR(50),
        admission_date DATETIME DEFAULT GETDATE(),
        attending_physician NVARCHAR(100),
        CONSTRAINT FK_Patient_Ward FOREIGN KEY (WardID) REFERENCES [WARD](WardID)
    );
END;
GO

IF OBJECT_ID('sp_create_allergy', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_allergy;
GO

CREATE PROCEDURE sp_create_allergy
AS
BEGIN
    IF OBJECT_ID('[ALLERGY]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [ALLERGY] (
        AllergyID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        allergen NVARCHAR(100),
        reaction NVARCHAR(MAX),
        severity NVARCHAR(50),
        allergy_type NVARCHAR(50),
        CONSTRAINT FK_Allergy_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

IF OBJECT_ID('sp_create_condition', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_condition;
GO

CREATE PROCEDURE sp_create_condition
AS
BEGIN
    IF OBJECT_ID('[CONDITION_AT_BIRTH]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [CONDITION_AT_BIRTH] (
        ConditionID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        condition_name NVARCHAR(100),
        condition_description NVARCHAR(MAX),
        CONSTRAINT FK_Condition_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

-- =====================================================
-- CHART DOMAIN - Charts & Associated Records
-- =====================================================

IF OBJECT_ID('sp_create_chart', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_chart;
GO

CREATE PROCEDURE sp_create_chart
AS
BEGIN
    IF OBJECT_ID('[CHART]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [CHART] (
        ChartID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        date_created DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Chart_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

IF OBJECT_ID('sp_create_medication', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_medication;
GO

CREATE PROCEDURE sp_create_medication
AS
BEGIN
    IF OBJECT_ID('[MEDICATION]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [MEDICATION] (
        MedID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        medicine_name NVARCHAR(100) NOT NULL,
        quantity INT NOT NULL,
        expiry_dates DATE NOT NULL,
        statuses NVARCHAR(50),
        medicine_notes NVARCHAR(255),
        CONSTRAINT FK_Medication_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

IF OBJECT_ID('sp_create_medication_schedule', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_medication_schedule;
GO

CREATE PROCEDURE sp_create_medication_schedule
AS
BEGIN
    IF OBJECT_ID('[MEDICATION_SCHEDULE]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [MEDICATION_SCHEDULE] (
        SchedID INT IDENTITY(1,1) PRIMARY KEY,
        MedID INT NOT NULL,
        dosage_amount INT,
        frequency INT,
        last_administered DATETIME,
        next_scheduled DATETIME,
        taken_status NVARCHAR(50),
        CONSTRAINT FK_MedSched_Medication FOREIGN KEY (MedID) REFERENCES [MEDICATION](MedID)
    );
END;
GO

IF OBJECT_ID('sp_create_diagnosis', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_diagnosis;
GO

CREATE PROCEDURE sp_create_diagnosis
AS
BEGIN
    IF OBJECT_ID('[DIAGNOSIS]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [DIAGNOSIS] (
        DiagnosisID INT IDENTITY(1,1) PRIMARY KEY,
        ChartID INT NOT NULL,
        diagnosis_name NVARCHAR(100),
        diagnosis_description NVARCHAR(MAX),
        CONSTRAINT FK_Diagnosis_Chart FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO

IF OBJECT_ID('sp_create_vital_sign', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_vital_sign;
GO

CREATE PROCEDURE sp_create_vital_sign
AS
BEGIN
    IF OBJECT_ID('[VITAL_SIGN]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [VITAL_SIGN] (
        VitalID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        vital_type NVARCHAR(50),
        value NVARCHAR(50),
        unit NVARCHAR(20),
        time_taken DATETIME DEFAULT GETDATE(),
        SystolicBP INT,
        DiastolicBP INT,
        CONSTRAINT FK_VitalSign_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

IF OBJECT_ID('sp_create_notes', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_notes;
GO

CREATE PROCEDURE sp_create_notes
AS
BEGIN
    IF OBJECT_ID('[NOTES]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [NOTES] (
        NotesID INT IDENTITY(1,1) PRIMARY KEY,
        ChartID INT NOT NULL,
        NurseID INT NOT NULL,
        note_title NVARCHAR(100),
        note_description NVARCHAR(MAX),
        note_priority NVARCHAR(50),
        time_noted DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Notes_Chart FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID),
        CONSTRAINT FK_Notes_User FOREIGN KEY (NurseID) REFERENCES [USER](userID)
    );
END;
GO

IF OBJECT_ID('sp_create_logs', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_logs;
GO

CREATE PROCEDURE sp_create_logs
AS
BEGIN
    IF OBJECT_ID('[LOG]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [LOG] (
        LogID INT IDENTITY(1,1) PRIMARY KEY,
        userID INT,
        PatientID INT,
        actions NVARCHAR(100),
        descriptions NVARCHAR(MAX),
        log_status NVARCHAR(50),
        logtime DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Log_User FOREIGN KEY (userID) REFERENCES [USER](userID),
        CONSTRAINT FK_Log_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO

-- =====================================================
-- MASTER SETUP PROCEDURE - Creates all tables
-- =====================================================

IF OBJECT_ID('sp_setup_schema', 'P') IS NOT NULL
    DROP PROCEDURE sp_setup_schema;
GO

CREATE PROCEDURE sp_setup_schema
AS
BEGIN
    SET NOCOUNT ON;

    PRINT 'Creating Core Tables...';
    EXEC sp_create_user;
    EXEC sp_create_ward;

    PRINT 'Creating SubType Tables...';
    EXEC sp_create_nurse;
    EXEC sp_create_admin;

    PRINT 'Creating Patient Domain Tables...';
    EXEC sp_create_patient;
    EXEC sp_create_allergy;
    EXEC sp_create_condition;

    PRINT 'Creating Chart Domain Tables...';
    EXEC sp_create_chart;
    EXEC sp_create_medication;
    EXEC sp_create_medication_schedule;
    EXEC sp_create_diagnosis;
    EXEC sp_create_vital_sign;
    EXEC sp_create_notes;
    EXEC sp_create_logs;

    PRINT 'All tables created successfully!';
END;
GO

-- =====================================================
-- EXECUTE SETUP - CREATE ALL TABLES
-- =====================================================

PRINT '====================================================';
PRINT 'NURSING MANAGEMENT SYSTEM - DATABASE SETUP';
PRINT '====================================================';
PRINT '';
PRINT 'Initializing database schema...';
PRINT '';

EXEC sp_setup_schema;

-- =====================================================
-- VERIFICATION
-- =====================================================

PRINT '';
PRINT '====================================================';
PRINT 'DATABASE SETUP COMPLETE!';
PRINT '====================================================';
PRINT '';
PRINT 'Tables Created:';

SELECT 
    TABLE_NAME,
    'OK' AS Status
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'dbo'
ORDER BY TABLE_NAME;

PRINT '';
PRINT 'Your database is ready for the Laravel application!';
PRINT '';
PRINT 'Key Tables:';
PRINT '  USER - User authentication & profiles';
PRINT '  PATIENT - Patient records';
PRINT '  MEDICATION - Medication inventory';
PRINT '  MEDICATION_SCHEDULE - Dosage schedules';
PRINT '  VITAL_SIGN - Vital signs measurements';
PRINT '  NOTES - Chart notes';
PRINT '  LOG - Activity logging';
PRINT '';
PRINT '====================================================';
