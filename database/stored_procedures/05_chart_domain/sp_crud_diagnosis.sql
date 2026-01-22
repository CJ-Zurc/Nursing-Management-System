IF OBJECT_ID('sp_read_all_diagnoses', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_diagnoses;
GO

CREATE PROCEDURE sp_read_all_diagnoses
    @ChartID INT
AS
BEGIN
    SELECT DiagID, ChartID, diagnosisName, descriptions, status, date_recorded
    FROM [DIAGNOSIS]
    WHERE ChartID = @ChartID
    ORDER BY date_recorded DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_all_diagnoses_by_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_diagnoses_by_patient;
GO

CREATE PROCEDURE sp_read_all_diagnoses_by_patient
    @PatientID INT
AS
BEGIN
    SELECT D.DiagID, D.ChartID, D.diagnosisName, D.descriptions, D.status, D.date_recorded
    FROM [DIAGNOSIS] D
    INNER JOIN [CHART] C ON D.ChartID = C.ChartID
    WHERE C.PatientID = @PatientID
    ORDER BY D.date_recorded DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_diagnosis_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_diagnosis_by_id;
GO

CREATE PROCEDURE sp_read_diagnosis_by_id
    @DiagID INT
AS
BEGIN
    SELECT DiagID, ChartID, diagnosisName, descriptions, status, date_recorded
    FROM [DIAGNOSIS]
    WHERE DiagID = @DiagID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_diagnosis_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_diagnosis_record;
GO

CREATE PROCEDURE sp_create_diagnosis_record
    @ChartID INT,
    @diagnosisName NVARCHAR(100),
    @descriptions NVARCHAR(255),
    @status NVARCHAR(50)
AS
BEGIN
    -- Validate inputs
    IF @diagnosisName IS NULL OR @diagnosisName = ''
        THROW 50010, 'Diagnosis name cannot be empty', 1;
    IF LEN(@diagnosisName) > 100
        THROW 50011, 'Diagnosis name must be 100 characters or less', 1;
    IF @descriptions IS NULL OR @descriptions = ''
        THROW 50012, 'Description cannot be empty', 1;
    IF LEN(@descriptions) > 255
        THROW 50013, 'Description must be 255 characters or less', 1;
    IF @status NOT IN ('Active', 'Resolved', 'Under Review')
        THROW 50014, 'Status must be Active, Resolved, or Under Review', 1;
    
    INSERT INTO [DIAGNOSIS] (ChartID, diagnosisName, descriptions, status, date_recorded)
    VALUES (@ChartID, @diagnosisName, @descriptions, @status, GETDATE());
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS DiagID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_diagnosis_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_diagnosis_record;
GO

CREATE PROCEDURE sp_update_diagnosis_record
    @DiagID INT,
    @diagnosisName NVARCHAR(100),
    @descriptions NVARCHAR(255),
    @status NVARCHAR(50)
AS
BEGIN
    -- Validate inputs
    IF @diagnosisName IS NULL OR @diagnosisName = ''
        THROW 50010, 'Diagnosis name cannot be empty', 1;
    IF LEN(@diagnosisName) > 100
        THROW 50011, 'Diagnosis name must be 100 characters or less', 1;
    IF @descriptions IS NULL OR @descriptions = ''
        THROW 50012, 'Description cannot be empty', 1;
    IF LEN(@descriptions) > 255
        THROW 50013, 'Description must be 255 characters or less', 1;
    IF @status NOT IN ('Active', 'Resolved', 'Under Review')
        THROW 50014, 'Status must be Active, Resolved, or Under Review', 1;
    
    UPDATE [DIAGNOSIS]
    SET diagnosisName = @diagnosisName,
        descriptions = @descriptions,
        status = @status,
        date_recorded = GETDATE()
    WHERE DiagID = @DiagID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_diagnosis_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_diagnosis_record;
GO

CREATE PROCEDURE sp_delete_diagnosis_record
    @DiagID INT
AS
BEGIN
    DELETE FROM [DIAGNOSIS]
    WHERE DiagID = @DiagID;
END;
GO
