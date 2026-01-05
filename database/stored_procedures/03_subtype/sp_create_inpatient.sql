IF OBJECT_ID('sp_create_inpatient', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_inpatient;
GO

CREATE PROCEDURE sp_create_inpatient
AS
BEGIN
    IF OBJECT_ID('[INPATIENT]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [INPATIENT] (
        PatientID INT PRIMARY KEY,
        WardID INT,
        roomNumber NVARCHAR(20),
        bedNumber NVARCHAR(20),
        admission_date DATE,
        discharge_date DATE,
        attending_physician NVARCHAR(100),
        CONSTRAINT FK_Inpatient_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID),
        CONSTRAINT FK_Inpatient_Ward FOREIGN KEY (WardID) REFERENCES [WARD](WardID)
    );
END;
GO
