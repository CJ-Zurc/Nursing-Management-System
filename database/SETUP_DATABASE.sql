-- =====================================================
-- NURSING MANAGEMENT SYSTEM - COMPLETE DATABASE SETUP
-- =====================================================
-- This script sets up the entire database schema and all stored procedures
-- Run this script on your SQL Server to initialize the system
-- =====================================================

-- Set the database context (change 'nursing_db' to your actual database name)
USE nursing_db;
GO

-- =====================================================
-- STEP 1: Execute Core Procedure Definitions
-- =====================================================
PRINT 'Step 1: Setting up Core Procedures...'
GO

-- Core User Management
:r "02_core/sp_create_user.sql"
:r "02_core/sp_crud_user.sql"

-- Ward Management
:r "02_core/sp_create_ward.sql"
:r "02_core/sp_crud_ward.sql"

-- =====================================================
-- STEP 2: Execute SubType Procedure Definitions
-- =====================================================
PRINT 'Step 2: Setting up SubType Procedures...'
GO

-- Nurse SubType
:r "03_subtype/sp_create_nurse.sql"

-- Admin SubType
:r "03_subtype/sp_create_admin.sql"

-- =====================================================
-- STEP 3: Execute Patient Domain Procedures
-- =====================================================
PRINT 'Step 3: Setting up Patient Domain Procedures...'
GO

-- Patient Management
:r "04_patient_domain/sp_create_patient.sql"
:r "04_patient_domain/sp_crud_patient.sql"

-- Allergies
:r "04_patient_domain/sp_create_allergy.sql"
:r "04_patient_domain/sp_crud_allergy.sql"

-- Medical Conditions
:r "04_patient_domain/sp_create_condition.sql"
:r "04_patient_domain/sp_crud_condition.sql"

-- =====================================================
-- STEP 4: Execute Chart Domain Procedures
-- =====================================================
PRINT 'Step 4: Setting up Chart Domain Procedures...'
GO

-- Charts
:r "05_chart_domain/sp_create_chart.sql"

-- Medications (Updated schema)
:r "05_chart_domain/sp_create_medication.sql"
:r "05_chart_domain/sp_crud_medication.sql"

-- Medication Schedule (Updated schema)
:r "05_chart_domain/sp_create_medcsched.sql"

-- Diagnosis
:r "05_chart_domain/sp_create_diagnosis.sql"
:r "05_chart_domain/sp_crud_diagnosis.sql"

-- Vital Signs
:r "05_chart_domain/sp_create_vital_sign.sql"
:r "05_chart_domain/sp_crud_vital_sign.sql"

-- =====================================================
-- STEP 5: Execute Extra/Utility Procedures
-- =====================================================
PRINT 'Step 5: Setting up Extra/Utility Procedures...'
GO

-- Notes (Updated schema with ChartID FK)
:r "06_extras/sp_create_notes.sql"

-- Medication Schedule CRUD (Updated)
:r "06_extras/sp_create_medcsched.sql"

-- Logs
:r "06_extras/sp_create_logs.sql"

-- Dashboard Procedures
:r "06_extras/sp_dashboard_procedures.sql"

-- Export Procedures
:r "06_extras/sp_export_procedures.sql"

-- =====================================================
-- STEP 6: Execute Master Setup Procedure
-- =====================================================
PRINT 'Step 6: Creating Master Schema Setup Procedure...'
GO

-- Create the master setup procedure that creates all tables
:r "99_sp_setup_schema.sql"

-- =====================================================
-- STEP 7: Initialize Database Schema
-- =====================================================
PRINT 'Step 7: Initializing Database Schema...'
GO

EXEC sp_setup_schema;

-- =====================================================
-- STEP 8: Verify Setup
-- =====================================================
PRINT 'Step 8: Verifying Database Setup...'
GO

-- Check if all tables were created successfully
SELECT 
    TABLE_NAME,
    'Table Created Successfully' AS Status
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'dbo'
ORDER BY TABLE_NAME;

-- =====================================================
-- SETUP COMPLETE
-- =====================================================
PRINT '=====================================================';
PRINT 'DATABASE SETUP COMPLETE!';
PRINT '=====================================================';
PRINT 'The following tables have been created:';
PRINT '  - USER (Core user management)';
PRINT '  - WARD (Ward management)';
PRINT '  - NURSE (Nurse subtype)';
PRINT '  - ADMIN (Admin subtype)';
PRINT '  - PATIENT (Patient records)';
PRINT '  - ALLERGY (Patient allergies)';
PRINT '  - CONDITION_AT_BIRTH (Medical conditions)';
PRINT '  - CHART (Patient charts)';
PRINT '  - MEDICATION (Medications - Quantity/Expiry based)';
PRINT '  - MEDICATION_SCHEDULE (Dosage schedules with administration tracking)';
PRINT '  - DIAGNOSIS (Chart diagnoses)';
PRINT '  - VITAL_SIGN (Patient vital signs with export support)';
PRINT '  - NOTES (Chart notes - now linked to CHART instead of PATIENT)';
PRINT '  - LOG (Activity logging with color-coded actions)';
PRINT '=====================================================';
PRINT 'Key Updates in This Version:';
PRINT '  1. NOTES table now uses ChartID foreign key (not PatientID)';
PRINT '  2. MEDICATION simplified to Quantity/Expiry tracking';
PRINT '  3. MEDICATION_SCHEDULE added with dosage_amount and frequency fields';
PRINT '  4. New sp_administer_medication procedure for medication tracking';
PRINT '  5. VITAL_SIGN table supports patient vital signs recording and export';
PRINT '  6. Improved logging with color-coded actions (Create/Update/Delete)';
PRINT '=====================================================';
