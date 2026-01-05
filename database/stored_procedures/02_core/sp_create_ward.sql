IF OBJECT_ID('sp_create_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_ward;
GO

CREATE PROCEDURE sp_create_ward
AS
BEGIN
    IF OBJECT_ID('[WARD]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [WARD] (
        WardID INT IDENTITY(1,1) PRIMARY KEY,
        WardName NVARCHAR(100) NOT NULL,
        nRooms INT NOT NULL,
        occupiedRooms INT DEFAULT 0,
        laRooms INT DEFAULT 0
    );
END;
GO
