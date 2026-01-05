IF OBJECT_ID('sp_create_allergy', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_allergy;
GO

CREATE PROCEDURE sp_create_allergy
AS
BEGIN
    IF OBJECT_ID('[ALLERGY]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [ALLERGY] (
        AllergyID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        allergen NVARCHAR(100),
        reaction NVARCHAR(100),
        severity NVARCHAR(50),
        allergy_type NVARCHAR(50),
        CONSTRAINT FK_Allergy_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO
