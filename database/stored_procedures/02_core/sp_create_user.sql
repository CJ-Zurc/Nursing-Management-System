IF OBJECT_ID('sp_create_user', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_user;
GO

CREATE PROCEDURE sp_create_user
AS
BEGIN
    IF OBJECT_ID('[USER]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [USER] (
        userID INT IDENTITY(1,1) PRIMARY KEY,
        first_name NVARCHAR(100) NOT NULL,
        last_name NVARCHAR(100) NOT NULL,
        contact_number NVARCHAR(20),
        email NVARCHAR(150) UNIQUE NOT NULL,
        [password] NVARCHAR(255) NOT NULL CHECK (LEN([password]) >= 8),
        created_at DATETIME DEFAULT GETDATE(),
        system_role NVARCHAR(50) NOT NULL
    );
END;
GO
