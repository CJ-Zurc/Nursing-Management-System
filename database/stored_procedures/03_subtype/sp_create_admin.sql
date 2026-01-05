IF OBJECT_ID('sp_create_admin', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_admin;
GO

CREATE PROCEDURE sp_create_admin
AS
BEGIN
    IF OBJECT_ID('[ADMIN]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [ADMIN] (
        userID INT PRIMARY KEY,
        admin_level NVARCHAR(50),
        CONSTRAINT FK_Admin_User FOREIGN KEY (userID) REFERENCES [USER](userID)
    );
END;
GO
