IF OBJECT_ID('sp_read_all_medications', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_medications;
GO

CREATE PROCEDURE sp_read_all_medications
    @PatientID INT
AS
BEGIN
    SELECT MedID, PatientID, medicine_name, quantity, expiry_dates, statuses, medicine_notes
    FROM [MEDICATION]
    WHERE PatientID = @PatientID
    ORDER BY MedID DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_medication_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_medication_by_id;
GO

CREATE PROCEDURE sp_read_medication_by_id
    @MedID INT
AS
BEGIN
    SELECT MedID, PatientID, medicine_name, quantity, expiry_dates, statuses, medicine_notes
    FROM [MEDICATION]
    WHERE MedID = @MedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_medication_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_medication_record;
GO

CREATE PROCEDURE sp_create_medication_record
    @PatientID INT,
    @medicine_name NVARCHAR(100),
    @quantity INT,
    @expiry_dates DATE,
    @statuses NVARCHAR(50),
    @medicine_notes NVARCHAR(255)
AS
BEGIN
    INSERT INTO [MEDICATION] (PatientID, medicine_name, quantity, expiry_dates, statuses, medicine_notes)
    VALUES (@PatientID, @medicine_name, @quantity, @expiry_dates, @statuses, @medicine_notes);
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS MedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_medication_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_medication_record;
GO

CREATE PROCEDURE sp_update_medication_record
    @MedID INT,
    @medicine_name NVARCHAR(100),
    @quantity INT,
    @expiry_dates DATE,
    @statuses NVARCHAR(50),
    @medicine_notes NVARCHAR(255)
AS
BEGIN
    UPDATE [MEDICATION]
    SET medicine_name = @medicine_name,
        quantity = @quantity,
        expiry_dates = @expiry_dates,
        statuses = @statuses,
        medicine_notes = @medicine_notes
    WHERE MedID = @MedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_medication_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_medication_record;
GO

CREATE PROCEDURE sp_delete_medication_record
    @MedID INT
AS
BEGIN
    DELETE FROM [MEDICATION]
    WHERE MedID = @MedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_medication_quantity', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_medication_quantity;
GO

CREATE PROCEDURE sp_update_medication_quantity
    @MedID INT,
    @new_quantity INT
AS
BEGIN
    UPDATE [MEDICATION]
    SET quantity = @new_quantity
    WHERE MedID = @MedID;
END;
GO
