IF OBJECT_ID('sp_read_all_medication_schedules', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_medication_schedules;
GO

CREATE PROCEDURE sp_read_all_medication_schedules
    @MedID INT
AS
BEGIN
    SELECT SchedID, MedID, dosage_amount, frequency, times_administered, last_administered, taken_status
    FROM [MEDICATION_SCHEDULE]
    WHERE MedID = @MedID
    ORDER BY SchedID DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_medication_schedule_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_medication_schedule_by_id;
GO

CREATE PROCEDURE sp_read_medication_schedule_by_id
    @SchedID INT
AS
BEGIN
    SELECT SchedID, MedID, dosage_amount, frequency, times_administered, last_administered, taken_status
    FROM [MEDICATION_SCHEDULE]
    WHERE SchedID = @SchedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_medication_schedule_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_medication_schedule_record;
GO

CREATE PROCEDURE sp_create_medication_schedule_record
    @MedID INT,
    @dosage_amount INT,
    @frequency INT
AS
BEGIN
    INSERT INTO [MEDICATION_SCHEDULE] (MedID, dosage_amount, frequency, times_administered, taken_status)
    VALUES (@MedID, @dosage_amount, @frequency, 0, 'Pending');
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS SchedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_medication_schedule_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_medication_schedule_record;
GO

CREATE PROCEDURE sp_update_medication_schedule_record
    @SchedID INT,
    @dosage_amount INT,
    @frequency INT,
    @taken_status NVARCHAR(50)
AS
BEGIN
    UPDATE [MEDICATION_SCHEDULE]
    SET dosage_amount = @dosage_amount,
        frequency = @frequency,
        taken_status = @taken_status
    WHERE SchedID = @SchedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_administer_medication', 'P') IS NOT NULL
    DROP PROCEDURE sp_administer_medication;
GO

CREATE PROCEDURE sp_administer_medication
    @SchedID INT
AS
BEGIN
    DECLARE @MedID INT, @DosageAmount INT, @Frequency INT, @TimesAdministered INT, @NewStatus NVARCHAR(50), @CurrentQuantity INT;
    
    SELECT @MedID = MedID, @DosageAmount = dosage_amount, @Frequency = frequency, @TimesAdministered = times_administered
    FROM [MEDICATION_SCHEDULE]
    WHERE SchedID = @SchedID;
    
    SELECT @CurrentQuantity = quantity FROM [MEDICATION] WHERE MedID = @MedID;
    
    -- Check if already fully administered
    IF @TimesAdministered >= @Frequency
    BEGIN
        RAISERROR('This medication schedule has already been fully administered.', 16, 1);
        RETURN;
    END;
    
    -- Check if enough quantity
    IF @CurrentQuantity < @DosageAmount
    BEGIN
        RAISERROR('Insufficient medication quantity to administer.', 16, 1);
        RETURN;
    END;
    
    -- Increment times administered
    SET @TimesAdministered = @TimesAdministered + 1;
    
    -- Determine status: if times_administered >= frequency, mark as 'Administered', otherwise 'Pending'
    IF @TimesAdministered >= @Frequency
        SET @NewStatus = 'Administered';
    ELSE
        SET @NewStatus = 'Pending';
    
    -- Update medication quantity
    UPDATE [MEDICATION]
    SET quantity = quantity - @DosageAmount
    WHERE MedID = @MedID;
    
    -- Update schedule status and times administered
    UPDATE [MEDICATION_SCHEDULE]
    SET times_administered = @TimesAdministered,
        taken_status = @NewStatus,
        last_administered = GETDATE()
    WHERE SchedID = @SchedID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_medication_schedule_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_medication_schedule_record;
GO

CREATE PROCEDURE sp_delete_medication_schedule_record
    @SchedID INT
AS
BEGIN
    DELETE FROM [MEDICATION_SCHEDULE]
    WHERE SchedID = @SchedID;
END;
GO
