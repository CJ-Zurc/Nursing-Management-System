IF OBJECT_ID('sp_create_notes', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_notes;
GO

CREATE PROCEDURE sp_create_notes
AS
BEGIN
    IF OBJECT_ID('[NOTES]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [NOTES] (
        NotesID INT IDENTITY(1,1) PRIMARY KEY,
        ChartID INT NOT NULL,
        note_title NVARCHAR(100),
        note_description NVARCHAR(MAX),
        note_priority NVARCHAR(50),
        time_noted DATETIME DEFAULT GETDATE(),

        CONSTRAINT FK_Notes_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO
-- ============================================
IF OBJECT_ID('sp_read_all_notes', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_notes;
GO

CREATE PROCEDURE sp_read_all_notes
    @ChartID INT
AS
BEGIN
    SELECT N.NotesID, N.ChartID, N.note_title, N.note_description, N.note_priority, N.time_noted,
           U.first_name + ' ' + U.last_name AS NurseName
    FROM [NOTES] N
    INNER JOIN [CHART] C ON N.ChartID = C.ChartID
    INNER JOIN [USER] U ON C.NurseID = U.userID
    WHERE N.ChartID = @ChartID
    ORDER BY N.time_noted DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_all_notes_by_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_notes_by_patient;
GO

CREATE PROCEDURE sp_read_all_notes_by_patient
    @PatientID INT
AS
BEGIN
    SELECT N.NotesID, N.ChartID, N.note_title, N.note_description, N.note_priority, N.time_noted,
           U.first_name + ' ' + U.last_name AS NurseName
    FROM [NOTES] N
    INNER JOIN [CHART] C ON N.ChartID = C.ChartID
    INNER JOIN [USER] U ON C.NurseID = U.userID
    WHERE C.PatientID = @PatientID
    ORDER BY N.time_noted DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_note_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_note_by_id;
GO

CREATE PROCEDURE sp_read_note_by_id
    @NotesID INT
AS
BEGIN
    SELECT N.NotesID, N.ChartID, N.note_title, N.note_description, N.note_priority, N.time_noted,
           U.first_name + ' ' + U.last_name AS NurseName
    FROM [NOTES] N
    INNER JOIN [CHART] C ON N.ChartID = C.ChartID
    INNER JOIN [USER] U ON C.NurseID = U.userID
    WHERE N.NotesID = @NotesID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_note_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_note_record;
GO

CREATE PROCEDURE sp_create_note_record
    @ChartID INT,
    @note_title NVARCHAR(100),
    @note_description NVARCHAR(MAX),
    @note_priority NVARCHAR(50)
AS
BEGIN
    INSERT INTO [NOTES] (ChartID, note_title, note_description, note_priority, time_noted)
    VALUES (@ChartID, @note_title, @note_description, @note_priority, GETDATE());
    
    SELECT CAST(SCOPE_IDENTITY() AS INT) AS NotesID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_note_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_note_record;
GO

CREATE PROCEDURE sp_update_note_record
    @NotesID INT,
    @note_title NVARCHAR(100),
    @note_description NVARCHAR(MAX),
    @note_priority NVARCHAR(50)
AS
BEGIN
    UPDATE [NOTES]
    SET note_title = @note_title,
        note_description = @note_description,
        note_priority = @note_priority
    WHERE NotesID = @NotesID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_delete_note_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_delete_note_record;
GO

CREATE PROCEDURE sp_delete_note_record
    @NotesID INT
AS
BEGIN
    DELETE FROM [NOTES]
    WHERE NotesID = @NotesID;
END;
GO