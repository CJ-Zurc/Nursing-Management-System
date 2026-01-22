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
        diagnosisName NVARCHAR(100) NOT NULL,
        descriptions NVARCHAR(255) NOT NULL,
        status NVARCHAR(50) NOT NULL,
        date_recorded DATETIME DEFAULT GETDATE(),

        CONSTRAINT FK_Diagnosis_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID),
        CONSTRAINT CK_DIAGNOSIS_STATUS
            CHECK (status IN ('Active', 'Resolved', 'Under Review'))
    );
END;
GO
