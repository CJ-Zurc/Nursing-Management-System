IF OBJECT_ID('sp_create_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_patient;
GO

CREATE PROCEDURE sp_create_patient
AS
BEGIN
    IF OBJECT_ID('[PATIENT]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [PATIENT] (
        PatientID INT IDENTITY(1,1) PRIMARY KEY,
        WardID INT NOT NULL,
        first_name NVARCHAR(100) NOT NULL,
        last_name NVARCHAR(100) NOT NULL,
        dateOfBirth DATE,
        Sex NVARCHAR(10) NOT NULL,
        contact_Number NVARCHAR(20),
        Guardian NVARCHAR(100),
        guardian_Number NVARCHAR(20),
        address NVARCHAR(200),
        height FLOAT NOT NULL,
        weight FLOAT NOT NULL,
        blood_type NVARCHAR(10),
        roomNumber NVARCHAR(20) NOT NULL,
        bedNumber NVARCHAR(20) NOT NULL,
        admission_date DATE,
        discharge_date DATE,
        attending_physician NVARCHAR(100),
        patient_status NVARCHAR(50) DEFAULT 'Admitted',
        isActive BIT DEFAULT 1,
        CONSTRAINT FK_Patient_Ward 
            FOREIGN KEY (WardID) REFERENCES [WARD](WardID)
    );
END;
GO
