IF OBJECT_ID('sp_create_outpatient', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_outpatient;
GO

CREATE PROCEDURE sp_create_outpatient
AS
BEGIN
    IF OBJECT_ID('[OUTPATIENT]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [OUTPATIENT] (
        PatientID INT PRIMARY KEY,
        return_date DATE,
        visit_Reason NVARCHAR(200),
        CONSTRAINT FK_Outpatient_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO
