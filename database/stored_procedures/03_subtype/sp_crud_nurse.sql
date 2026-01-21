IF OBJECT_ID('sp_read_all_nurses', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_nurses;
GO

CREATE PROCEDURE sp_read_all_nurses
AS
BEGIN
    SELECT N.userID, U.first_name, U.last_name, U.email, U.contact_number, 
           N.WardID, N.license_number, N.nurseActive, N.role, W.WardName
    FROM [NURSE] N
    INNER JOIN [USER] U ON N.userID = U.userID
    INNER JOIN [WARD] W ON N.WardID = W.WardID
    ORDER BY U.first_name;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_nurses_by_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_nurses_by_ward;
GO

CREATE PROCEDURE sp_read_nurses_by_ward
    @WardID INT
AS
BEGIN
    SELECT N.userID, U.first_name, U.last_name, U.email, U.contact_number, 
           N.WardID, N.license_number, N.nurseActive, N.role, W.WardName
    FROM [NURSE] N
    INNER JOIN [USER] U ON N.userID = U.userID
    INNER JOIN [WARD] W ON N.WardID = W.WardID
    WHERE N.WardID = @WardID
    ORDER BY U.first_name;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_nurse_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_nurse_by_id;
GO

CREATE PROCEDURE sp_read_nurse_by_id
    @userID INT
AS
BEGIN
    SELECT N.userID, U.first_name, U.last_name, U.email, U.contact_number, 
           N.WardID, N.license_number, N.nurseActive, N.role, W.WardName
    FROM [NURSE] N
    INNER JOIN [USER] U ON N.userID = U.userID
    INNER JOIN [WARD] W ON N.WardID = W.WardID
    WHERE N.userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_nurse_account', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_nurse_account;
GO

CREATE PROCEDURE sp_create_nurse_account
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @password NVARCHAR(255),
    @WardID INT,
    @license_number NVARCHAR(50),
    @role NVARCHAR(50) = 'Nurse'
AS
BEGIN
    DECLARE @userID INT;
    
    INSERT INTO [USER] (first_name, last_name, contact_number, email, [password], created_at, system_role)
    VALUES (@first_name, @last_name, @contact_number, @email, @password, GETDATE(), 'Nurse');
    
    SET @userID = CAST(SCOPE_IDENTITY() AS INT);
    
    INSERT INTO [NURSE] (userID, WardID, license_number, nurseActive, role)
    VALUES (@userID, @WardID, @license_number, 1, @role);
    
    SELECT @userID AS userID, @first_name AS first_name, @last_name AS last_name;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_nurse', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_nurse;
GO

CREATE PROCEDURE sp_update_nurse
    @userID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @WardID INT,
    @license_number NVARCHAR(50),
    @role NVARCHAR(50)
AS
BEGIN
    UPDATE [USER]
    SET first_name = @first_name,
        last_name = @last_name,
        contact_number = @contact_number,
        email = @email
    WHERE userID = @userID;
    
    UPDATE [NURSE]
    SET WardID = @WardID,
        license_number = @license_number,
        role = @role
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_nurse_soft', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_nurse_soft;
GO

CREATE PROCEDURE sp_delete_nurse_soft
    @userID INT
AS
BEGIN
    UPDATE [NURSE]
    SET nurseActive = 0
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_deactivate_nurse', 'P') IS NOT NULL
    DROP PROCEDURE sp_deactivate_nurse;
GO

CREATE PROCEDURE sp_deactivate_nurse
    @userID INT
AS
BEGIN
    UPDATE [NURSE]
    SET nurseActive = 0
    WHERE userID = @userID;
END;
GO
