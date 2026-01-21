IF OBJECT_ID('sp_read_all_wards', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_wards;
GO

CREATE PROCEDURE sp_read_all_wards
AS
BEGIN
    SELECT WardID, WardName, nRooms, occupiedRooms, laRooms 
    FROM [WARD]
    ORDER BY WardID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_ward_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_ward_by_id;
GO

CREATE PROCEDURE sp_read_ward_by_id
    @WardID INT
AS
BEGIN
    SELECT WardID, WardName, nRooms, occupiedRooms, laRooms 
    FROM [WARD]
    WHERE WardID = @WardID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_ward;
GO

CREATE PROCEDURE sp_update_ward
    @WardID INT,
    @WardName NVARCHAR(100),
    @nRooms INT,
    @occupiedRooms INT,
    @laRooms INT
AS
BEGIN
    UPDATE [WARD]
    SET WardName = @WardName,
        nRooms = @nRooms,
        occupiedRooms = @occupiedRooms,
        laRooms = @laRooms
    WHERE WardID = @WardID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_ward;
GO

CREATE PROCEDURE sp_delete_ward
    @WardID INT
AS
BEGIN
    DELETE FROM [WARD]
    WHERE WardID = @WardID;
END;
GO

-- ============================================
-- Insert hardcoded wards
IF OBJECT_ID('sp_seed_wards', 'P') IS NOT NULL
    DROP PROCEDURE sp_seed_wards;
GO

CREATE PROCEDURE sp_seed_wards
AS
BEGIN
    IF NOT EXISTS(SELECT 1 FROM [WARD])
    BEGIN
        INSERT INTO [WARD] (WardName, nRooms, occupiedRooms, laRooms)
        VALUES 
            ('General Ward', 100, 0, 100),
            ('Intensive Care Unit', 100, 0, 100),
            ('Surgical Ward', 100, 0, 100),
            ('Maternity Ward', 100, 0, 100),
            ('Pediatric Ward', 100, 0, 100),
            ('Emergency Ward', 100, 0, 100),
            ('Psychiatric Ward', 100, 0, 100),
            ('Rehabilitation Ward', 100, 0, 100),
            ('Isolation Ward', 100, 0, 100);
    END
END;
GO
