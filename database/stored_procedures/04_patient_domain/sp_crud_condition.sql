IF OBJECT_ID('sp_read_all_conditions', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_conditions;
GO

CREATE PROCEDURE sp_read_all_conditions
    @PatientID INT
AS
BEGIN
    SELECT ConditionID, PatientID, condition_name, status
    FROM [CONDITIONS]
    WHERE PatientID = @PatientID
    ORDER BY ConditionID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_condition_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_condition_by_id;
GO

CREATE PROCEDURE sp_read_condition_by_id
    @ConditionID INT
AS
BEGIN
    SELECT ConditionID, PatientID, condition_name, status
    FROM [CONDITIONS]
    WHERE ConditionID = @ConditionID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_condition_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_condition_record;
GO

CREATE PROCEDURE sp_create_condition_record
    @PatientID INT,
    @condition_name NVARCHAR(100),
    @status NVARCHAR(50)
AS
BEGIN
    INSERT INTO [CONDITIONS] (PatientID, condition_name, status)
    VALUES (@PatientID, @condition_name, @status);
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS ConditionID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_condition_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_condition_record;
GO

CREATE PROCEDURE sp_update_condition_record
    @ConditionID INT,
    @condition_name NVARCHAR(100),
    @status NVARCHAR(50)
AS
BEGIN
    UPDATE [CONDITIONS]
    SET condition_name = @condition_name,
        status = @status
    WHERE ConditionID = @ConditionID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_condition_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_condition_record;
GO

CREATE PROCEDURE sp_delete_condition_record
    @ConditionID INT
AS
BEGIN
    DELETE FROM [CONDITIONS]
    WHERE ConditionID = @ConditionID;
END;
GO
