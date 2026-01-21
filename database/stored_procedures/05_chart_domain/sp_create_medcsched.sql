IF OBJECT_ID('sp_create_medication_schedule', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_medication_schedule;
GO

CREATE PROCEDURE sp_create_medication_schedule
AS
BEGIN
    IF OBJECT_ID('[MEDICATION_SCHEDULE]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [MEDICATION_SCHEDULE] (
        SchedID INT IDENTITY(1,1) PRIMARY KEY,
        MedID INT NOT NULL,
        dosage_amount INT,
        frequency INT,
        times_administered INT DEFAULT 0,
        last_administered DATETIME,
        taken_status NVARCHAR(50),

        CONSTRAINT FK_MedSched_Medication
            FOREIGN KEY (MedID) REFERENCES [MEDICATION](MedID)
    );
END;
GO
