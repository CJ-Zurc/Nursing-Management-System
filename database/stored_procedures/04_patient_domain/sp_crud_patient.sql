IF OBJECT_ID('sp_read_all_patients', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_all_patients;
GO

CREATE PROCEDURE sp_read_all_patients
AS
BEGIN
    SELECT P.PatientID, P.WardID, ISNULL(W.WardName, 'Unknown Ward') AS WardName, P.first_name, P.last_name, P.dateOfBirth, 
           P.Sex, P.contact_Number, P.Guardian, P.guardian_Number, P.address, P.height, 
           P.weight, P.blood_type, P.roomNumber, P.bedNumber, 
           P.admission_date, P.discharge_date, P.attending_physician, P.patient_status, P.isActive
    FROM [PATIENT] P
    LEFT JOIN [WARD] W ON P.WardID = W.WardID
    WHERE P.isActive = 1
    ORDER BY P.admission_date DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_patients_by_ward', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_patients_by_ward;
GO

CREATE PROCEDURE sp_read_patients_by_ward
    @WardID INT
AS
BEGIN
    SELECT P.PatientID, P.WardID, ISNULL(W.WardName, 'Unknown Ward') AS WardName, P.first_name, P.last_name, P.dateOfBirth, 
           P.Sex, P.contact_Number, P.Guardian, P.guardian_Number, P.address, P.height, 
           P.weight, P.blood_type, P.roomNumber, P.bedNumber, 
           P.admission_date, P.discharge_date, P.attending_physician, P.patient_status, P.isActive
    FROM [PATIENT] P
    LEFT JOIN [WARD] W ON P.WardID = W.WardID
    WHERE P.WardID = @WardID AND P.isActive = 1
    ORDER BY P.first_name;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_patient_by_id', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_patient_by_id;
GO

CREATE PROCEDURE sp_read_patient_by_id
    @PatientID INT
AS
BEGIN
    SELECT P.PatientID, P.WardID, ISNULL(W.WardName, 'Unknown Ward') AS WardName, P.first_name, P.last_name, P.dateOfBirth, 
           P.Sex, P.contact_Number, P.Guardian, P.guardian_Number, P.address, P.height, 
           P.weight, P.blood_type, P.roomNumber, P.bedNumber, 
           P.admission_date, P.discharge_date, P.attending_physician, P.patient_status, P.isActive
    FROM [PATIENT] P
    LEFT JOIN [WARD] W ON P.WardID = W.WardID
    WHERE P.PatientID = @PatientID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_create_patient_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_create_patient_record;
GO

CREATE PROCEDURE sp_create_patient_record
    @WardID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @dateOfBirth DATE,
    @Sex NVARCHAR(10),
    @contact_Number NVARCHAR(20),
    @Guardian NVARCHAR(100),
    @guardian_Number NVARCHAR(20),
    @address NVARCHAR(200),
    @height FLOAT,
    @weight FLOAT,
    @blood_type NVARCHAR(10),
    @roomNumber NVARCHAR(20),
    @bedNumber NVARCHAR(20),
    @admission_date DATE,
    @attending_physician NVARCHAR(100),
    @NurseID INT = NULL
AS
BEGIN
    DECLARE @PatientID INT;
    DECLARE @AssignedNurseID INT;

    -- Insert patient
    INSERT INTO [PATIENT] (WardID, first_name, last_name, dateOfBirth, Sex, contact_Number, 
                           Guardian, guardian_Number, address, height, weight, blood_type, 
                           roomNumber, bedNumber, admission_date, 
                           attending_physician, patient_status, isActive)
    VALUES (@WardID, @first_name, @last_name, @dateOfBirth, @Sex, @contact_Number, 
            @Guardian, @guardian_Number, @address, @height, @weight, @blood_type, 
            @roomNumber, @bedNumber, @admission_date, 
            @attending_physician, 'Admitted', 1);
    
    SET @PatientID = CAST(SCOPE_IDENTITY() AS INT);

    -- Determine which nurse to assign to the chart
    IF @NurseID IS NOT NULL
    BEGIN
        SET @AssignedNurseID = @NurseID;
    END
    ELSE
    BEGIN
        -- Get the first active nurse in this ward
        SELECT TOP 1 @AssignedNurseID = N.userID
        FROM [NURSE] N
        WHERE N.WardID = @WardID AND N.nurseActive = 1
        ORDER BY N.userID;
        
        -- If no active nurse found, try to get any nurse in the ward
        IF @AssignedNurseID IS NULL
        BEGIN
            SELECT TOP 1 @AssignedNurseID = N.userID
            FROM [NURSE] N
            WHERE N.WardID = @WardID
            ORDER BY N.userID;
        END
    END;

    -- Create chart if we have a nurse assigned
    IF @AssignedNurseID IS NOT NULL
    BEGIN
        INSERT INTO [CHART] (PatientID, NurseID, date_created)
        VALUES (@PatientID, @AssignedNurseID, GETDATE());
    END;

    SELECT @PatientID AS PatientID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_update_patient_record', 'P') IS NOT NULL
    DROP PROCEDURE sp_update_patient_record;
GO

CREATE PROCEDURE sp_update_patient_record
    @PatientID INT,
    @WardID INT,
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @dateOfBirth DATE,
    @Sex NVARCHAR(10),
    @contact_Number NVARCHAR(20),
    @Guardian NVARCHAR(100),
    @guardian_Number NVARCHAR(20),
    @address NVARCHAR(200),
    @height FLOAT,
    @weight FLOAT,
    @blood_type NVARCHAR(10),
    @roomNumber NVARCHAR(20),
    @bedNumber NVARCHAR(20),
    @attending_physician NVARCHAR(100),
    @patient_status NVARCHAR(50)
AS
BEGIN
    UPDATE [PATIENT]
    SET WardID = @WardID,
        first_name = @first_name,
        last_name = @last_name,
        dateOfBirth = @dateOfBirth,
        Sex = @Sex,
        contact_Number = @contact_Number,
        Guardian = @Guardian,
        guardian_Number = @guardian_Number,
        address = @address,
        height = @height,
        weight = @weight,
        blood_type = @blood_type,
        roomNumber = @roomNumber,
        bedNumber = @bedNumber,
        attending_physician = @attending_physician,
        patient_status = @patient_status
    WHERE PatientID = @PatientID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_discharge_patient', 'P') IS NOT NULL
    DROP PROCEDURE sp_discharge_patient;
GO

CREATE PROCEDURE sp_discharge_patient
    @PatientID INT,
    @discharge_date DATE
AS
BEGIN
    UPDATE [PATIENT]
    SET discharge_date = @discharge_date,
        patient_status = 'Discharged',
        isActive = 0
    WHERE PatientID = @PatientID;
END;
GO
