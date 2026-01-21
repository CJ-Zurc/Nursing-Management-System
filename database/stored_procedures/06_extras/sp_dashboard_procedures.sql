-- Dashboard stored procedures

-- ============================================
IF OBJECT_ID('sp_read_today_admissions', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_today_admissions;
GO

CREATE PROCEDURE sp_read_today_admissions
    @Date DATE
AS
BEGIN
    SELECT TOP 5 P.PatientID, P.WardID, ISNULL(W.WardName, 'Unknown Ward') AS WardName, 
           P.first_name, P.last_name, P.dateOfBirth, P.Sex, P.contact_Number, P.Guardian, 
           P.guardian_Number, P.address, P.height, P.weight, P.blood_type,
           P.roomNumber, P.bedNumber, P.admission_date, P.discharge_date, P.attending_physician, 
           P.patient_status, P.isActive
    FROM [PATIENT] P
    LEFT JOIN [WARD] W ON P.WardID = W.WardID
    WHERE CAST(P.admission_date AS DATE) = @Date AND P.isActive = 1
    ORDER BY P.admission_date DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_patient_stats', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_patient_stats;
GO

CREATE PROCEDURE sp_read_patient_stats
AS
BEGIN
    SELECT patient_status, COUNT(*) as count
    FROM [PATIENT]
    WHERE isActive = 1
    GROUP BY patient_status;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_admission_trends', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_admission_trends;
GO

CREATE PROCEDURE sp_read_admission_trends
AS
BEGIN
    SELECT CAST(admission_date AS DATE) as date, COUNT(*) as count
    FROM [PATIENT]
    WHERE admission_date >= DATEADD(DAY, -7, GETDATE())
    GROUP BY CAST(admission_date AS DATE)
    ORDER BY date DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_total_active_patients', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_total_active_patients;
GO

CREATE PROCEDURE sp_read_total_active_patients
AS
BEGIN
    SELECT COUNT(*) as total_patients
    FROM [PATIENT]
    WHERE isActive = 1;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_ward_capacity', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_ward_capacity;
GO

CREATE PROCEDURE sp_read_ward_capacity
AS
BEGIN
    SELECT 
        W.WardID,
        W.WardName,
        W.nRooms as total_capacity,
        COUNT(CASE WHEN P.isActive = 1 THEN 1 END) as occupied_beds,
        W.nRooms - COUNT(CASE WHEN P.isActive = 1 THEN 1 END) as remaining_capacity
    FROM [WARD] W
    LEFT JOIN [PATIENT] P ON W.WardID = P.WardID
    GROUP BY W.WardID, W.WardName, W.nRooms
    ORDER BY W.WardID;
END;
GO

-- ============================================
IF OBJECT_ID('sp_read_monthly_admissions', 'P') IS NOT NULL
    DROP PROCEDURE sp_read_monthly_admissions;
GO

CREATE PROCEDURE sp_read_monthly_admissions
AS
BEGIN
    SELECT 
        MONTH(admission_date) as month_num,
        DATENAME(MONTH, admission_date) as month_name,
        COUNT(*) as count
    FROM [PATIENT]
    WHERE YEAR(admission_date) = YEAR(GETDATE())
    GROUP BY MONTH(admission_date), DATENAME(MONTH, admission_date)
    ORDER BY MONTH(admission_date);
END;
GO
