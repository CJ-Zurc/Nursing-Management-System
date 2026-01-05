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
        ChartID INT NOT NULL,
        medicine_name NVARCHAR(100),
        dosage NVARCHAR(50),
        frequency NVARCHAR(50),
        status NVARCHAR(50),

        CONSTRAINT FK_Medication_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO
