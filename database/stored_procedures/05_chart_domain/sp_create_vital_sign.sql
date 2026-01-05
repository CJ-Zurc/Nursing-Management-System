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
        vital_type NVARCHAR(50),
        value NVARCHAR(50),
        unit NVARCHAR(20),
        time_taken DATETIME DEFAULT GETDATE(),
        SystolicBP INT,
        DiastolicBP INT,

        CONSTRAINT FK_VitalSign_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO
