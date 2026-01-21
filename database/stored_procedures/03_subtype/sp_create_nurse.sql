IF OBJECT_ID('sp_create_nurse', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_nurse;
GO

CREATE PROCEDURE sp_create_nurse
AS
BEGIN
    IF OBJECT_ID('[NURSE]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [NURSE] (
        userID INT PRIMARY KEY,
        WardID INT NOT NULL,
        license_number NVARCHAR(50) NOT NULL,
        nurseActive BIT DEFAULT 1,
        role NVARCHAR(50),
        CONSTRAINT FK_Nurse_User FOREIGN KEY (userID) REFERENCES [USER](userID),
        CONSTRAINT FK_Nurse_Ward FOREIGN KEY (WardID) REFERENCES [WARD](WardID)
    );
END;
GO
