IF OBJECT_ID('sp_read_all_admins', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_admins;
GO

CREATE PROCEDURE sp_read_all_admins
AS
BEGIN
    SELECT A.userID, U.first_name, U.last_name, U.email, U.contact_number, 
           A.admin_level, U.created_at
    FROM [ADMIN] A
    INNER JOIN [USER] U ON A.userID = U.userID
    ORDER BY U.first_name;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_admin_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_admin_by_id;
GO

CREATE PROCEDURE sp_read_admin_by_id
    @userID INT
AS
BEGIN
    SELECT A.userID, U.first_name, U.last_name, U.email, U.contact_number, 
           A.admin_level, U.created_at
    FROM [ADMIN] A
    INNER JOIN [USER] U ON A.userID = U.userID
    WHERE A.userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_admin_account', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_admin_account;
GO

CREATE PROCEDURE sp_create_admin_account
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @password NVARCHAR(255),
    @admin_level NVARCHAR(50) = 'Standard'
AS
BEGIN
    DECLARE @userID INT;
    
    INSERT INTO [USER] (first_name, last_name, contact_number, email, [password], created_at, system_role)
    VALUES (@first_name, @last_name, @contact_number, @email, @password, GETDATE(), 'Admin');
    
    SET @userID = CAST(SCOPE_IDENTITY() AS INT);
    
    INSERT INTO [ADMIN] (userID, admin_level)
    VALUES (@userID, @admin_level);
    
    SELECT @userID AS userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_admin', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_admin;
GO

CREATE PROCEDURE sp_update_admin
    @userID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @admin_level NVARCHAR(50)
AS
BEGIN
    UPDATE [USER]
    SET first_name = @first_name,
        last_name = @last_name,
        contact_number = @contact_number,
        email = @email
    WHERE userID = @userID;
    
    UPDATE [ADMIN]
    SET admin_level = @admin_level
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_admin', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_admin;
GO

CREATE PROCEDURE sp_delete_admin
    @userID INT
AS
BEGIN
    DELETE FROM [ADMIN] WHERE userID = @userID;
    DELETE FROM [USER] WHERE userID = @userID;
END;
GO
