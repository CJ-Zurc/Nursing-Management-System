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
        ChartID INT NOT NULL,
        vital_type NVARCHAR(50) NOT NULL,
        value NVARCHAR(50) NOT NULL,
        unit NVARCHAR(20) NOT NULL,
        time_taken DATETIME DEFAULT GETDATE(),
        SystolicBP INT,
        DiastolicBP INT,

        CONSTRAINT FK_VitalSign_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID),
        CONSTRAINT CK_VITAL_SYSTOLIC_BP
            CHECK (SystolicBP IS NULL OR (SystolicBP >= 0 AND SystolicBP <= 300)),
        CONSTRAINT CK_VITAL_DIASTOLIC_BP
            CHECK (DiastolicBP IS NULL OR (DiastolicBP >= 0 AND DiastolicBP <= 200))
    );
END;
GO
