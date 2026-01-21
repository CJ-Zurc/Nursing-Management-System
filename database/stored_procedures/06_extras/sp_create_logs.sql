IF OBJECT_ID('sp_create_logs', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_logs;
GO

CREATE PROCEDURE sp_create_logs
AS
BEGIN
    IF OBJECT_ID('[LOGS]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [LOGS] (
        LogID INT IDENTITY(1,1) PRIMARY KEY,
        userID INT NOT NULL,
        PatientID INT NOT NULL,
        actions NVARCHAR(100),
        descriptions NVARCHAR(50),
        log_status NVARCHAR(50),
        logtime DATETIME DEFAULT GETDATE(),

        CONSTRAINT FK_Logs_User 
            FOREIGN KEY (userID) REFERENCES [USER](userID)
    );
END;
GO
-- ============================================
IF OBJECT_ID('sp_read_all_logs', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_logs;
GO

CREATE PROCEDURE sp_read_all_logs
AS
BEGIN
    SELECT L.LogID, L.userID, L.PatientID, L.actions, L.descriptions, L.log_status, L.logtime,
           U.first_name + ' ' + U.last_name AS UserName,
           P.first_name + ' ' + P.last_name AS PatientName
    FROM [LOGS] L
    INNER JOIN [USER] U ON L.userID = U.userID
    INNER JOIN [PATIENT] P ON L.PatientID = P.PatientID
    ORDER BY L.logtime DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_logs_by_date_range', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_logs_by_date_range;
GO

CREATE PROCEDURE sp_read_logs_by_date_range
    @StartDate DATETIME,
    @EndDate DATETIME
AS
BEGIN
    SELECT L.LogID, L.userID, L.PatientID, L.actions, L.descriptions, L.log_status, L.logtime,
           U.first_name + ' ' + U.last_name AS UserName,
           P.first_name + ' ' + P.last_name AS PatientName
    FROM [LOGS] L
    INNER JOIN [USER] U ON L.userID = U.userID
    INNER JOIN [PATIENT] P ON L.PatientID = P.PatientID
    WHERE L.logtime BETWEEN @StartDate AND @EndDate
    ORDER BY L.logtime DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_logs_by_user', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_logs_by_user;
GO

CREATE PROCEDURE sp_read_logs_by_user
    @userID INT
AS
BEGIN
    SELECT L.LogID, L.userID, L.PatientID, L.actions, L.descriptions, L.log_status, L.logtime,
           U.first_name + ' ' + U.last_name AS UserName,
           P.first_name + ' ' + P.last_name AS PatientName
    FROM [LOGS] L
    INNER JOIN [USER] U ON L.userID = U.userID
    INNER JOIN [PATIENT] P ON L.PatientID = P.PatientID
    WHERE L.userID = @userID
    ORDER BY L.logtime DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_log_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_log_by_id;
GO

CREATE PROCEDURE sp_read_log_by_id
    @LogID INT
AS
BEGIN
    SELECT LogID, userID, PatientID, actions, descriptions, log_status, logtime
    FROM [LOGS]
    WHERE LogID = @LogID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_log_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_log_record;
GO

CREATE PROCEDURE sp_create_log_record
    @userID INT,
    @PatientID INT,
    @actions NVARCHAR(100),
    @descriptions NVARCHAR(50),
    @log_status NVARCHAR(50)
AS
BEGIN
    INSERT INTO [LOGS] (userID, PatientID, actions, descriptions, log_status, logtime)
    VALUES (@userID, @PatientID, @actions, @descriptions, @log_status, GETDATE());
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS LogID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_log_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_log_record;
GO

CREATE PROCEDURE sp_delete_log_record
    @LogID INT
AS
BEGIN
    DELETE FROM [LOGS]
    WHERE LogID = @LogID;
END;
GO