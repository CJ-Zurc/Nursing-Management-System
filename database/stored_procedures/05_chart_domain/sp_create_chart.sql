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
        Remarks NVARCHAR(255),

        CONSTRAINT FK_Chart_Patient 
            FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID),

        CONSTRAINT FK_Chart_Nurse 
            FOREIGN KEY (NurseID) REFERENCES [NURSE](userID)
    );
END;
GO
