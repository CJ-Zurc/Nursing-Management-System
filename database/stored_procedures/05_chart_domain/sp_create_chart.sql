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
        NurseID INT NOT NULL,
        date_created DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Chart_Patient 
            FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID),
        CONSTRAINT FK_Chart_Nurse 
            FOREIGN KEY (NurseID) REFERENCES [NURSE](userID)
    );
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_chart_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_chart_by_id;
GO

CREATE PROCEDURE sp_read_chart_by_id
    @ChartID INT
AS
BEGIN
    SELECT ChartID, PatientID, NurseID, date_created
    FROM [CHART]
    WHERE ChartID = @ChartID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_charts_by_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_charts_by_patient;
GO

CREATE PROCEDURE sp_read_charts_by_patient
    @PatientID INT
AS
BEGIN
    SELECT C.ChartID, C.PatientID, C.date_created
    FROM [CHART] C
    WHERE C.PatientID = @PatientID
    ORDER BY C.date_created DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_chart_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_chart_record;
GO

CREATE PROCEDURE sp_create_chart_record
    @PatientID INT,
    @NurseID INT
AS
BEGIN
    INSERT INTO [CHART] (PatientID, NurseID, date_created)
    VALUES (@PatientID, @NurseID, GETDATE());
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS ChartID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_chart_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_chart_record;
GO

CREATE PROCEDURE sp_delete_chart_record
    @ChartID INT
AS
BEGIN
    DELETE FROM [CHART]
    WHERE ChartID = @ChartID;
END;
GO