IF OBJECT_ID('sp_create_diagnosis', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_diagnosis;
GO

CREATE PROCEDURE sp_create_diagnosis
AS
BEGIN
    IF OBJECT_ID('[DIAGNOSIS]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [DIAGNOSIS] (
        DiagID INT IDENTITY(1,1) PRIMARY KEY,
        ChartID INT NOT NULL,
        diagnosisName NVARCHAR (100),
        descriptions NVARCHAR(255),
        status NVARCHAR(50),
        date_recorded DATETIME DEFAULT GETDATE(),

        CONSTRAINT FK_Diagnosis_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO
