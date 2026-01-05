IF OBJECT_ID('sp_create_inventory', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_inventory;
GO

CREATE PROCEDURE sp_create_inventory
AS
BEGIN
    IF OBJECT_ID('[INVENTORY]', 'U') IS NOT NULL
        RETURN;

    CREATE TABLE [INVENTORY] (
        InventoryID INT IDENTITY(1,1) PRIMARY KEY,
        PatientID INT NOT NULL,
        medicineName NVARCHAR(100),
        quantity INT,
        item_type NVARCHAR(50),
        assigned_date DATE,
        expiry_date DATE,
        status NVARCHAR(50),
        medicine_notes NVARCHAR(255),
        CONSTRAINT FK_Inventory_Patient FOREIGN KEY (PatientID) REFERENCES [PATIENT](PatientID)
    );
END;
GO
