IF OBJECT_ID('sp_create_condition', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_condition;
GO

CREATE PROCEDURE sp_create_condition
AS
BEGIN
    IF OBJECT_ID('[CONDITIONS]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [CONDITIONS] (
        ConditionID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        condition_name NVARCHAR(100),
        status NVARCHAR(50),
        CONSTRAINT FK_Condition_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO
