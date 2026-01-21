IF OBJECT_ID('sp_read_all_users', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_users;
GO

CREATE PROCEDURE sp_read_all_users
AS
BEGIN
    SELECT userID, first_name, last_name, contact_number, email, created_at, system_role
    FROM [USER]
    ORDER BY created_at DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_user_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_user_by_id;
GO

CREATE PROCEDURE sp_read_user_by_id
    @userID INT
AS
BEGIN
    SELECT userID, first_name, last_name, contact_number, email, created_at, system_role
    FROM [USER]
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_user_by_email', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_user_by_email;
GO

CREATE PROCEDURE sp_read_user_by_email
    @email NVARCHAR(150)
AS
BEGIN
    SELECT userID, first_name, last_name, contact_number, email, [password], created_at, system_role
    FROM [USER]
    WHERE email = @email;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_user_account', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_user_account;
GO

CREATE PROCEDURE sp_create_user_account
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @password NVARCHAR(255),
    @system_role NVARCHAR(50)
AS
BEGIN
    INSERT INTO [USER] (first_name, last_name, contact_number, email, [password], created_at, system_role)
    VALUES (@first_name, @last_name, @contact_number, @email, @password, GETDATE(), @system_role);
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_user', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_user;
GO

CREATE PROCEDURE sp_update_user
    @userID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150)
AS
BEGIN
    UPDATE [USER]
    SET first_name = @first_name,
        last_name = @last_name,
        contact_number = @contact_number,
        email = @email
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_user', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_user;
GO

CREATE PROCEDURE sp_delete_user
    @userID INT
AS
BEGIN
    DELETE FROM [USER]
    WHERE userID = @userID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_user_profile', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_user_profile;
GO

CREATE PROCEDURE sp_update_user_profile
    @userID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @contact_number NVARCHAR(20),
    @email NVARCHAR(150),
    @password NVARCHAR(255) = NULL
AS
BEGIN
    IF @password IS NOT NULL AND @password != ''
    BEGIN
        UPDATE [USER]
        SET first_name = @first_name,
            last_name = @last_name,
            contact_number = @contact_number,
            email = @email,
            [password] = @password
        WHERE userID = @userID;
    END
    ELSE
    BEGIN
        UPDATE [USER]
        SET first_name = @first_name,
            last_name = @last_name,
            contact_number = @contact_number,
            email = @email
        WHERE userID = @userID;
    END
END;
GO
