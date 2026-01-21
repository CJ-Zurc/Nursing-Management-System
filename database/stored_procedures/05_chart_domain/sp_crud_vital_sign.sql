IF OBJECT_ID('sp_read_all_vital_signs', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_vital_signs;
GO

CREATE PROCEDURE sp_read_all_vital_signs
    @PatientID INT
AS
BEGIN
    SELECT V.VitalID, V.ChartID, V.vital_type, V.value, V.unit, V.time_taken, V.SystolicBP, V.DiastolicBP
    FROM [VITAL_SIGN] V
    INNER JOIN [CHART] C ON V.ChartID = C.ChartID
    WHERE C.PatientID = @PatientID
    ORDER BY V.time_taken DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_vital_sign_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_vital_sign_by_id;
GO

CREATE PROCEDURE sp_read_vital_sign_by_id
    @VitalID INT
AS
BEGIN
    SELECT V.VitalID, V.ChartID, V.vital_type, V.value, V.unit, V.time_taken, V.SystolicBP, V.DiastolicBP
    FROM [VITAL_SIGN] V
    WHERE V.VitalID = @VitalID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_vital_sign_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_vital_sign_record;
GO

CREATE PROCEDURE sp_create_vital_sign_record
    @PatientID INT,
    @vital_type NVARCHAR(50),
    @value NVARCHAR(50),
    @unit NVARCHAR(20),
    @SystolicBP INT = NULL,
    @DiastolicBP INT = NULL
AS
BEGIN
    -- Get the latest chart for this patient
    DECLARE @ChartID INT;
    SELECT TOP 1 @ChartID = ChartID FROM [CHART] WHERE PatientID = @PatientID ORDER BY ChartID DESC;
    
    -- If no chart exists, we can't create a vital sign
    IF @ChartID IS NULL
    BEGIN
        RAISERROR('No chart found for this patient. Create a chart first.', 16, 1);
        RETURN;
    END;
    
    INSERT INTO [VITAL_SIGN] (ChartID, vital_type, value, unit, time_taken, SystolicBP, DiastolicBP)
    VALUES (@ChartID, @vital_type, @value, @unit, GETDATE(), @SystolicBP, @DiastolicBP);
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS VitalID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_vital_sign_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_vital_sign_record;
GO

CREATE PROCEDURE sp_update_vital_sign_record
    @VitalID INT,
    @vital_type NVARCHAR(50),
    @value NVARCHAR(50),
    @unit NVARCHAR(20),
    @SystolicBP INT = NULL,
    @DiastolicBP INT = NULL
AS
BEGIN
    UPDATE [VITAL_SIGN]
    SET vital_type = @vital_type,
        value = @value,
        unit = @unit,
        SystolicBP = @SystolicBP,
        DiastolicBP = @DiastolicBP
    WHERE VitalID = @VitalID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_vital_sign_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_vital_sign_record;
GO

CREATE PROCEDURE sp_delete_vital_sign_record
    @VitalID INT
AS
BEGIN
    DELETE FROM [VITAL_SIGN]
    WHERE VitalID = @VitalID;
END;
GO
