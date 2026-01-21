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
        PatientID INT NOT NULL,
        medicine_name NVARCHAR(100) NOT NULL,
        quantity INT NOT NULL,
        expiry_dates DATETIME,
        statuses NVARCHAR(50) DEFAULT 'Active',
        medicine_notes NVARCHAR(MAX),
        created_at DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Medication_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END

