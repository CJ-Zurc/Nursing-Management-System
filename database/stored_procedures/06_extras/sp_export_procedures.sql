-- Export procedures for vital signs and other patient data

-- ============================================
IF OBJECT_ID('sp_export_vital_signs', 'P') IS NOT NULL
    DROP PROCEDURE sp_export_vital_signs;
GO

CREATE PROCEDURE sp_export_vital_signs
    @PatientID INT
AS
BEGIN
    SELECT V.VitalID, V.ChartID, V.vital_type, V.value, V.unit, V.time_taken, V.SystolicBP, V.DiastolicBP
    FROM [VITAL_SIGN] V
    INNER JOIN [CHART] C ON V.ChartID = C.ChartID
    WHERE C.PatientID = @PatientID
    ORDER BY V.time_taken DESC;
END;
GO

-- ============================================
IF OBJECT_ID('sp_export_patient_data', 'P') IS NOT NULL
    DROP PROCEDURE sp_export_patient_data;
GO

CREATE PROCEDURE sp_export_patient_data
    @PatientID INT
AS
BEGIN
    SELECT P.PatientID, P.first_name, P.last_name, P.dateOfBirth, P.Sex, 
           P.contact_Number, P.Guardian, P.guardian_Number, P.address,
           P.height, P.weight, P.blood_type, P.roomNumber, P.bedNumber,
           P.admission_date, P.discharge_date, P.attending_physician,
           P.patient_status, W.WardName
    FROM [PATIENT] P
    LEFT JOIN [WARD] W ON P.WardID = W.WardID
    WHERE P.PatientID = @PatientID;
END;
GO
