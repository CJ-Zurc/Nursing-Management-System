IF OBJECT_ID('sp_create_condition_at_birth', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_condition_at_birth;
GO

CREATE PROCEDURE sp_create_condition_at_birth
AS
BEGIN
    IF OBJECT_ID('[CONDITION_AT_BIRTH]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [CONDITION_AT_BIRTH] (
        ConditionID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        condition_name NVARCHAR(100),
        status NVARCHAR(50),
        CONSTRAINT FK_Condition_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO
