IF OBJECT_ID('sp_read_all_allergies', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_allergies;
GO

CREATE PROCEDURE sp_read_all_allergies
    @PatientID INT
AS
BEGIN
    SELECT AllergyID, PatientID, allergen, reaction, severity, allergy_type
    FROM [ALLERGY]
    WHERE PatientID = @PatientID
    ORDER BY AllergyID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_allergy_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_allergy_by_id;
GO

CREATE PROCEDURE sp_read_allergy_by_id
    @AllergyID INT
AS
BEGIN
    SELECT AllergyID, PatientID, allergen, reaction, severity, allergy_type
    FROM [ALLERGY]
    WHERE AllergyID = @AllergyID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_allergy_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_allergy_record;
GO

CREATE PROCEDURE sp_create_allergy_record
    @PatientID INT,
    @allergen NVARCHAR(100),
    @reaction NVARCHAR(100),
    @severity NVARCHAR(50),
    @allergy_type NVARCHAR(50)
AS
BEGIN
    INSERT INTO [ALLERGY] (PatientID, allergen, reaction, severity, allergy_type)
    VALUES (@PatientID, @allergen, @reaction, @severity, @allergy_type);
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS AllergyID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_allergy_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_allergy_record;
GO

CREATE PROCEDURE sp_update_allergy_record
    @AllergyID INT,
    @allergen NVARCHAR(100),
    @reaction NVARCHAR(100),
    @severity NVARCHAR(50),
    @allergy_type NVARCHAR(50)
AS
BEGIN
    UPDATE [ALLERGY]
    SET allergen = @allergen,
        reaction = @reaction,
        severity = @severity,
        allergy_type = @allergy_type
    WHERE AllergyID = @AllergyID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_allergy_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_allergy_record;
GO

CREATE PROCEDURE sp_delete_allergy_record
    @AllergyID INT
AS
BEGIN
    DELETE FROM [ALLERGY]
    WHERE AllergyID = @AllergyID;
END;
GO
