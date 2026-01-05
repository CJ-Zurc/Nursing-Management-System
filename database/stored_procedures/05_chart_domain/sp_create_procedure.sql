IF OBJECT_ID('sp_create_procedure', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_procedure;
GO

CREATE PROCEDURE sp_create_procedure
AS
BEGIN
    IF OBJECT_ID('[PROCEDURE]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [PROCEDURE] (
        ProcedureID INT IDENTITY(1,1) PRIMARY KEY,
        ChartID INT NOT NULL,
        procedure_date DATE,
        procedureName NVARCHAR(100),
        procedure_description NVARCHAR(255),

        CONSTRAINT FK_Procedure_Chart 
            FOREIGN KEY (ChartID) REFERENCES [CHART](ChartID)
    );
END;
GO
