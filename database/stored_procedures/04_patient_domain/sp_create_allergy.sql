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
        allergen NVARCHAR(100) NOT NULL,
        reaction NVARCHAR(100) NOT NULL,
        severity NVARCHAR(50) NOT NULL,
        allergy_type NVARCHAR(50) NOT NULL,
        CONSTRAINT FK_Allergy_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID),
        CONSTRAINT CK_ALLERGY_SEVERITY CHECK (severity IN ('Mild', 'Moderate', 'Severe'))
    );
END;
GO
