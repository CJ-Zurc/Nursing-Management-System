<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NurseController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $nurse = DB::select('EXEC sp_read_nurse_by_id @userID = ' . Auth::id())[0];
        
        // Get patients in this nurse's ward
        $patients = DB::select('EXEC sp_read_patients_by_ward @WardID = ' . $nurse->WardID);

        // Get recent logs by this nurse
        $recentLogs = DB::select('EXEC sp_read_logs_by_user @userID = ' . Auth::id());
        $recentLogs = array_slice($recentLogs, 0, 5);

        return view('nurse.dashboard', [
            'nurse' => $nurse,
            'patients' => $patients,
            'recentLogs' => $recentLogs,
            'patientCount' => count($patients),
        ]);
    }

    // ============ PATIENT LIST ============
    public function patients(Request $request)
    {
        $nurse = DB::select('EXEC sp_read_nurse_by_id @userID = ' . Auth::id())[0];
        $search = $request->query('search', '');
        
        if ($search) {
            $patients = DB::select('SELECT * FROM [PATIENT] WHERE WardID = ? AND (CAST(PatientID AS NVARCHAR(MAX)) LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR blood_type LIKE ?)', 
                [$nurse->WardID, '%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
        } else {
            $patients = DB::select('EXEC sp_read_patients_by_ward @WardID = ' . $nurse->WardID);
        }

        return view('nurse.patients.index', ['patients' => $patients, 'wardId' => $nurse->WardID, 'search' => $search]);
    }

    // ============ PATIENT PROFILE ============
    public function showPatient($id)
    {
        $patient = DB::select('EXEC sp_read_patient_by_id @PatientID = ' . $id)[0] ?? null;

        if (!$patient) {
            return redirect()->route('nurse.patients')->with('error', 'Patient not found');
        }

        // Verify nurse is on the same ward
        $nurse = DB::select('EXEC sp_read_nurse_by_id @userID = ' . Auth::id())[0];
        if ($nurse->WardID != $patient->WardID) {
            return redirect()->route('nurse.patients')->with('error', 'Access denied');
        }

        // Get patient's charts
        $charts = DB::select('EXEC sp_read_charts_by_patient @PatientID = ' . $id);

        // Get allergies
        $allergies = DB::select('EXEC sp_read_all_allergies @PatientID = ' . $id);

        // Get conditions
        $conditions = DB::select('EXEC sp_read_all_conditions @PatientID = ' . $id);

        // Get medications
        $medications = DB::select('EXEC sp_read_all_medications @PatientID = ' . $id);

        // Get vital signs
        $vitalSigns = DB::select('EXEC sp_read_all_vital_signs @PatientID = ' . $id);

        // Get notes (under charts)
        $notes = DB::select('EXEC sp_read_all_notes_by_patient @PatientID = ' . $id);

        // Get diagnoses (under charts)
        $diagnoses = DB::select('EXEC sp_read_all_diagnoses_by_patient @PatientID = ' . $id);

        return view('nurse.patients.show', [
            'patient' => $patient,
            'charts' => $charts,
            'allergies' => $allergies,
            'conditions' => $conditions,
            'medications' => $medications,
            'vitalSigns' => $vitalSigns,
            'notes' => $notes,
            'diagnoses' => $diagnoses,
        ]);
    }

    // ============ ALLERGIES ============
    public function storeAllergy(Request $request, $patientId)
    {
        $validated = $request->validate([
            'allergen' => 'required|string',
            'reaction' => 'required|string',
            'severity' => 'required|string',
            'allergy_type' => 'required|string',
        ]);

        DB::statement("EXEC sp_create_allergy_record
            @PatientID = " . $patientId . ",
            @allergen = N'" . str_replace("'", "''", $validated['allergen']) . "',
            @reaction = N'" . str_replace("'", "''", $validated['reaction']) . "',
            @severity = N'" . $validated['severity'] . "',
            @allergy_type = N'" . $validated['allergy_type'] . "'
        ");

        $this->logAction($patientId, 'Add Allergy', $validated['allergen']);

        return redirect()->route('nurse.patient.show', $patientId)->with('success', 'Allergy added successfully');
    }

    public function updateAllergy(Request $request, $allergyId)
    {
        $validated = $request->validate([
            'allergen' => 'required|string',
            'reaction' => 'required|string',
            'severity' => 'required|string',
            'allergy_type' => 'required|string',
        ]);

        DB::statement("EXEC sp_update_allergy_record
            @AllergyID = " . $allergyId . ",
            @allergen = N'" . str_replace("'", "''", $validated['allergen']) . "',
            @reaction = N'" . str_replace("'", "''", $validated['reaction']) . "',
            @severity = N'" . $validated['severity'] . "',
            @allergy_type = N'" . $validated['allergy_type'] . "'
        ");

        return back()->with('success', 'Allergy updated successfully');
    }

    public function deleteAllergy($allergyId, $patientId)
    {
        DB::statement('EXEC sp_delete_allergy_record @AllergyID = ' . $allergyId);
        $this->logAction($patientId, 'Delete Allergy', 'Allergy removed');

        return back()->with('success', 'Allergy deleted successfully');
    }

    // ============ CONDITIONS ============
    public function storeCondition(Request $request, $patientId)
    {
        $validated = $request->validate([
            'condition_name' => 'required|string',
            'status' => 'required|string',
        ]);

        DB::statement("EXEC sp_create_condition_record
            @PatientID = " . $patientId . ",
            @condition_name = N'" . str_replace("'", "''", $validated['condition_name']) . "',
            @status = N'" . $validated['status'] . "'
        ");

        $this->logAction($patientId, 'Add Condition', $validated['condition_name']);

        return redirect()->route('nurse.patient.show', $patientId)->with('success', 'Condition added successfully');
    }

    public function updateCondition(Request $request, $conditionId)
    {
        $validated = $request->validate([
            'condition_name' => 'required|string',
            'status' => 'required|string',
        ]);

        DB::statement("EXEC sp_update_condition_record
            @ConditionID = " . $conditionId . ",
            @condition_name = N'" . str_replace("'", "''", $validated['condition_name']) . "',
            @status = N'" . $validated['status'] . "'
        ");

        return back()->with('success', 'Condition updated successfully');
    }

    public function deleteCondition($conditionId, $patientId)
    {
        DB::statement('EXEC sp_delete_condition_record @ConditionID = ' . $conditionId);
        $this->logAction($patientId, 'Delete Condition', 'Condition removed');

        return back()->with('success', 'Condition deleted successfully');
    }

    // ============ MEDICATIONS ============
    public function storeMedication(Request $request, $patientId)
    {
        $validated = $request->validate([
            'medicine_name' => 'required|string',
            'quantity' => 'required|integer',
            'expiry_dates' => 'required|date|after:today',
            'statuses' => 'required|string',
            'medicine_notes' => 'nullable|string',
        ]);

        $result = DB::statement("EXEC sp_create_medication_record
            @PatientID = " . $patientId . ",
            @medicine_name = N'" . str_replace("'", "''", $validated['medicine_name']) . "',
            @quantity = " . $validated['quantity'] . ",
            @expiry_dates = N'" . $validated['expiry_dates'] . "',
            @statuses = N'" . str_replace("'", "''", $validated['statuses']) . "',
            @medicine_notes = N'" . (isset($validated['medicine_notes']) ? str_replace("'", "''", $validated['medicine_notes']) : '') . "'
        ");

        $this->logAction($patientId, 'Add Medication', $validated['medicine_name']);

        return redirect()->route('nurse.patient.show', $patientId)->with('success', 'Medication added successfully');
    }

    public function updateMedication(Request $request, $medId)
    {
        $validated = $request->validate([
            'medicine_name' => 'required|string',
            'quantity' => 'required|integer',
            'expiry_dates' => 'required|date|after:today',
            'statuses' => 'required|string',
            'medicine_notes' => 'nullable|string',
        ]);

        DB::statement("EXEC sp_update_medication_record
            @MedID = " . $medId . ",
            @medicine_name = N'" . str_replace("'", "''", $validated['medicine_name']) . "',
            @quantity = " . $validated['quantity'] . ",
            @expiry_dates = N'" . $validated['expiry_dates'] . "',
            @statuses = N'" . str_replace("'", "''", $validated['statuses']) . "',
            @medicine_notes = N'" . (isset($validated['medicine_notes']) ? str_replace("'", "''", $validated['medicine_notes']) : '') . "'
        ");

        return back()->with('success', 'Medication updated successfully');
    }

    public function updateMedicationQuantity(Request $request, $medId)
    {
        $validated = $request->validate([
            'quantity_change' => 'required|integer|min:1',
            'action' => 'required|in:add,subtract',
        ]);

        // Get the medication first
        $medication = DB::select('EXEC sp_read_medication_by_id @MedID = ' . $medId)[0] ?? null;
        
        if (!$medication) {
            return back()->with('error', 'Medication not found');
        }

        // Calculate new quantity
        $currentQuantity = (int)$medication->quantity;
        $change = (int)$validated['quantity_change'];
        $newQuantity = $validated['action'] === 'add' ? $currentQuantity + $change : $currentQuantity - $change;

        // Validate quantity doesn't go negative
        if ($newQuantity < 0) {
            return back()->with('error', 'Medication quantity cannot go below 0');
        }

        // Update the medication quantity
        DB::statement("EXEC sp_update_medication_quantity
            @MedID = " . $medId . ",
            @new_quantity = " . $newQuantity . "
        ");

        $action = $validated['action'] === 'add' ? 'Restocked' : 'Removed';
        $this->logAction($medication->PatientID, $action . ' Medication', 'Quantity changed from ' . $currentQuantity . ' to ' . $newQuantity . ' for ' . $medication->medicine_name);

        return back()->with('success', 'Medication quantity updated successfully');
    }

    public function deleteMedication($medId, $patientId)
    {
        DB::statement('EXEC sp_delete_medication_record @MedID = ' . $medId);
        $this->logAction($patientId, 'Delete Medication', 'Medication removed');

        return back()->with('success', 'Medication deleted successfully');
    }

    // ============ MEDICATION SCHEDULE ============
    public function storeMedicationSchedule(Request $request, $medId)
    {
        $validated = $request->validate([
            'dosage_amount' => 'required|integer|min:1',
            'frequency' => 'required|integer|min:1|max:24',
        ]);

        DB::statement("EXEC sp_create_medication_schedule_record
            @MedID = " . $medId . ",
            @dosage_amount = " . $validated['dosage_amount'] . ",
            @frequency = " . $validated['frequency'] . "
        ");

        return back()->with('success', 'Medication schedule created successfully');
    }

    public function updateMedicationSchedule(Request $request, $schedId)
    {
        $validated = $request->validate([
            'dosage_amount' => 'required|integer|min:1',
            'frequency' => 'required|integer|min:1',
            'taken_status' => 'nullable|string',
        ]);

        DB::statement("EXEC sp_update_medication_schedule_record
            @SchedID = " . $schedId . ",
            @dosage_amount = " . $validated['dosage_amount'] . ",
            @frequency = " . $validated['frequency'] . ",
            @taken_status = N'" . ($validated['taken_status'] ?? 'Pending') . "'
        ");

        return back()->with('success', 'Medication schedule updated successfully');
    }

    public function showMedicationSchedules($medId)
    {
        $medication = DB::select('EXEC sp_read_medication_by_id @MedID = ' . $medId)[0] ?? null;

        if (!$medication) {
            return redirect()->back()->with('error', 'Medication not found');
        }

        // Verify access
        $nurse = DB::select('EXEC sp_read_nurse_by_id @userID = ' . Auth::id())[0];
        $patient = DB::select('EXEC sp_read_patient_by_id @PatientID = ' . $medication->PatientID)[0];
        
        if ($nurse->WardID != $patient->WardID) {
            return redirect()->back()->with('error', 'Access denied');
        }

        $schedules = DB::select('EXEC sp_read_all_medication_schedules @MedID = ' . $medId);

        return view('nurse.medications.schedules', [
            'medication' => $medication,
            'schedules' => $schedules,
        ]);
    }

    public function administerMedication($schedId)
    {
        $schedule = DB::select('EXEC sp_read_medication_schedule_by_id @SchedID = ' . $schedId)[0] ?? null;

        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found');
        }

        // Read medication to check current quantity before attempting administration
        $medication = DB::select('EXEC sp_read_medication_by_id @MedID = ' . $schedule->MedID)[0] ?? null;
        if (!$medication) {
            return redirect()->back()->with('error', 'Medication not found');
        }

        // Prevent attempting to administer when insufficient quantity
        if ((int)$medication->quantity < (int)$schedule->dosage_amount) {
            return redirect()->back()->with('error', 'Insufficient medication quantity to administer.');
        }

        try {
            // Execute the administer procedure
            DB::statement("EXEC sp_administer_medication @SchedID = " . $schedId);
        } catch (\Exception $e) {
            // If stored procedure raised an error, surface a friendly message
            $msg = $e->getMessage();
            if (stripos($msg, 'Insufficient medication quantity') !== false) {
                return redirect()->back()->with('error', 'Insufficient medication quantity to administer.');
            }
            return redirect()->back()->with('error', 'Failed to administer medication: ' . $msg);
        }

        // Get updated medication info for logging
        $medication = DB::select('EXEC sp_read_medication_by_id @MedID = ' . $schedule->MedID)[0];
        $this->logAction($medication->PatientID, 'Administer Medication', 'Administered ' . $schedule->dosage_amount . ' units of ' . $medication->medicine_name);

        return redirect()->route('nurse.patient.show', $medication->PatientID)->with('success', 'Medication administered successfully. Quantity reduced by ' . $schedule->dosage_amount . '.');
    }

    // ============ CHARTS & VITALS ============
    public function createChart(Request $request, $patientId)
    {
        // The NurseID is actually the userID in the NURSE table
        $nurseId = Auth::id();

        $result = DB::statement("EXEC sp_create_chart_record
            @PatientID = " . $patientId . ",
            @NurseID = " . $nurseId . "
        ");

        $this->logAction($patientId, 'Create Chart', 'New chart created');

        return redirect()->route('nurse.patient.show', $patientId)->with('success', 'Chart created successfully');
    }

    public function showChart($chartId, $patientId)
    {
        $chart = DB::select('EXEC sp_read_chart_by_id @ChartID = ' . $chartId);
        if (empty($chart)) {
            return redirect()->back()->with('error', 'Chart not found');
        }
        $chart = $chart[0];

        $vitalSigns = DB::select('EXEC sp_read_all_vital_signs @PatientID = ' . $patientId);
        $diagnoses = DB::select('EXEC sp_read_all_diagnoses @ChartID = ' . $chartId);
        $notes = DB::select('EXEC sp_read_all_notes @ChartID = ' . $chartId);

        return view('nurse.charts.show', [
            'chart' => $chart,
            'patientId' => $patientId,
            'vitalSigns' => $vitalSigns,
            'diagnoses' => $diagnoses,
            'notes' => $notes,
        ]);
    }

    public function storeVitalSign(Request $request, $chartId)
    {
        $chartResult = DB::select('EXEC sp_read_chart_by_id @ChartID = ' . $chartId);
        if (empty($chartResult)) {
            return back()->with('error', 'Chart not found with ID: ' . $chartId);
        }
        $chart = $chartResult[0];
        
        $validated = $request->validate([
            'temperature' => 'nullable|numeric|min:30|max:45',
            'heart_rate' => 'nullable|integer|min:0|max:200',
            'systolic_bp' => 'nullable|integer|min:0|max:300',
            'diastolic_bp' => 'nullable|integer|min:0|max:200',
            'respiratory_rate' => 'nullable|integer|min:0|max:60',
            'oxygen_sat' => 'nullable|numeric|min:0|max:100',
        ]);

        // Record each vital sign if provided
        if ($validated['temperature']) {
            DB::statement("EXEC sp_create_vital_sign_record
                @PatientID = " . $chart->PatientID . ",
                @vital_type = 'Temperature',
                @value = '" . $validated['temperature'] . "',
                @unit = '°C',
                @SystolicBP = NULL,
                @DiastolicBP = NULL
            ");
        }

        if ($validated['heart_rate']) {
            DB::statement("EXEC sp_create_vital_sign_record
                @PatientID = " . $chart->PatientID . ",
                @vital_type = 'Heart Rate',
                @value = '" . $validated['heart_rate'] . "',
                @unit = 'bpm',
                @SystolicBP = NULL,
                @DiastolicBP = NULL
            ");
        }

        if ($validated['systolic_bp'] && $validated['diastolic_bp']) {
            DB::statement("EXEC sp_create_vital_sign_record
                @PatientID = " . $chart->PatientID . ",
                @vital_type = 'Blood Pressure',
                @value = '" . $validated['systolic_bp'] . "/" . $validated['diastolic_bp'] . "',
                @unit = 'mmHg',
                @SystolicBP = " . $validated['systolic_bp'] . ",
                @DiastolicBP = " . $validated['diastolic_bp'] . "
            ");
        }

        if ($validated['respiratory_rate']) {
            DB::statement("EXEC sp_create_vital_sign_record
                @PatientID = " . $chart->PatientID . ",
                @vital_type = 'Respiratory Rate',
                @value = '" . $validated['respiratory_rate'] . "',
                @unit = 'breaths/min',
                @SystolicBP = NULL,
                @DiastolicBP = NULL
            ");
        }

        if ($validated['oxygen_sat']) {
            DB::statement("EXEC sp_create_vital_sign_record
                @PatientID = " . $chart->PatientID . ",
                @vital_type = 'Oxygen Saturation',
                @value = '" . $validated['oxygen_sat'] . "',
                @unit = '%',
                @SystolicBP = NULL,
                @DiastolicBP = NULL
            ");
        }

        return back()->with('success', 'Vital signs recorded successfully');
    }

    public function storeDiagnosis(Request $request, $chartId)
    {
        // Validate chart exists
        $chart = DB::select('EXEC sp_read_chart_by_id @ChartID = ' . $chartId);
        if (empty($chart)) {
            return back()->with('error', 'Chart not found');
        }
        
        // Validate input
        $validated = $request->validate([
            'diagnosisName' => 'required|string|max:100',
            'descriptions' => 'required|string|max:500',
            'status' => 'required|in:Active,Resolved,Under Review',
        ]);

        // Create diagnosis record
        DB::statement("EXEC sp_create_diagnosis_record
            @ChartID = " . $chartId . ",
            @diagnosisName = N'" . str_replace("'", "''", $validated['diagnosisName']) . "',
            @descriptions = N'" . str_replace("'", "''", $validated['descriptions']) . "',
            @status = N'" . $validated['status'] . "'
        ");

        return back()->with('success', 'Diagnosis recorded successfully');
    }

    public function storeNote(Request $request, $chartId)
    {
        // Validate chart exists
        $chart = DB::select('EXEC sp_read_chart_by_id @ChartID = ' . $chartId);
        if (empty($chart)) {
            return back()->with('error', 'Chart not found');
        }
        
        // Validate input
        $validated = $request->validate([
            'note_title' => 'required|string|max:100',
            'note_description' => 'required|string|max:2000',
            'note_priority' => 'required|in:Low,Medium,High',
        ]);

        // Create note record (NurseID comes from Chart)
        DB::statement("EXEC sp_create_note_record
            @ChartID = " . $chartId . ",
            @note_title = N'" . str_replace("'", "''", $validated['note_title']) . "',
            @note_description = N'" . str_replace("'", "''", $validated['note_description']) . "',
            @note_priority = N'" . $validated['note_priority'] . "'
        ");

        return back()->with('success', 'Note added successfully');
    }

    public function updateVitalSign(Request $request, $vitalId)
    {
        $validated = $request->validate([
            'vital_type' => 'required|string',
            'value' => 'required|numeric',
            'unit' => 'required|string',
            'SystolicBP' => 'nullable|numeric',
            'DiastolicBP' => 'nullable|numeric',
        ]);

        DB::statement("EXEC sp_update_vital_sign_record
            @VitalID = " . $vitalId . ",
            @vital_type = N'" . str_replace("'", "''", $validated['vital_type']) . "',
            @value = " . $validated['value'] . ",
            @unit = N'" . str_replace("'", "''", $validated['unit']) . "',
            @SystolicBP = " . ($validated['SystolicBP'] ?? 'NULL') . ",
            @DiastolicBP = " . ($validated['DiastolicBP'] ?? 'NULL') . "
        ");

        return back()->with('success', 'Vital sign updated successfully');
    }

    public function deleteVitalSign($vitalId, $patientId)
    {
        DB::statement('EXEC sp_delete_vital_sign_record @VitalID = ' . $vitalId);
        return back()->with('success', 'Vital sign deleted successfully');
    }

    public function updateDiagnosis(Request $request, $diagnosisId)
    {
        $validated = $request->validate([
            'diagnosisName' => 'required|string|max:100',
            'descriptions' => 'required|string|max:500',
            'status' => 'required|in:Active,Resolved,Under Review',
        ]);

        DB::statement("EXEC sp_update_diagnosis_record
            @DiagID = " . $diagnosisId . ",
            @diagnosisName = N'" . str_replace("'", "''", $validated['diagnosisName']) . "',
            @descriptions = N'" . str_replace("'", "''", $validated['descriptions']) . "',
            @status = N'" . $validated['status'] . "'
        ");

        return back()->with('success', 'Diagnosis updated successfully');
    }

    public function deleteDiagnosis($diagnosisId, $patientId)
    {
        DB::statement('EXEC sp_delete_diagnosis_record @DiagID = ' . $diagnosisId);
        return back()->with('success', 'Diagnosis deleted successfully');
    }

    public function updateNote(Request $request, $noteId)
    {
        $validated = $request->validate([
            'note_title' => 'required|string|max:100',
            'note_description' => 'required|string|max:2000',
            'note_priority' => 'required|in:Low,Medium,High',
        ]);

        DB::statement("EXEC sp_update_note_record
            @NotesID = " . $noteId . ",
            @note_title = N'" . str_replace("'", "''", $validated['note_title']) . "',
            @note_description = N'" . str_replace("'", "''", $validated['note_description']) . "',
            @note_priority = N'" . $validated['note_priority'] . "'
        ");

        return back()->with('success', 'Note updated successfully');
    }

    public function deleteNote($noteId, $patientId)
    {
        DB::statement('EXEC sp_delete_note_record @NotesID = ' . $noteId);
        return back()->with('success', 'Note deleted successfully');
    }

    // ============ HELPER METHODS ============
    private function logAction($patientId, $action, $description)
    {
        DB::statement('EXEC sp_create_log_record
            @userID = ?,
            @PatientID = ?,
            @actions = ?,
            @descriptions = ?,
            @log_status = ?
        ', [
            Auth::id(),
            $patientId,
            $action,
            substr($description, 0, 50),
            'Success',
        ]);
    }

    public function exportVitals($chartId)
    {
        $chart = DB::select('EXEC sp_read_chart_by_id @ChartID = ?', [$chartId])[0];
        $vitals = DB::select('EXEC sp_read_all_vital_signs @PatientID = ?', [$chart->PatientID]);

        $csv = "VitalID,Type,Value,Unit,SystolicBP,DiastolicBP,TimeTaken\n";
        foreach ($vitals as $vital) {
            $csv .= "{$vital->VitalID},{$vital->vital_type},{$vital->value},{$vital->unit},{$vital->SystolicBP},{$vital->DiastolicBP},{$vital->time_taken}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="vitals-chart-' . $chartId . '-' . date('Y-m-d') . '.csv"');
    }

    public function exportPatientVitals($patientId)
    {
        $patient = DB::select('EXEC sp_read_patient_by_id @PatientID = ?', [$patientId])[0];
        $vitals = DB::select('EXEC sp_read_all_vital_signs @PatientID = ?', [$patientId]);

        $csv = "VitalID,Type,Value,Unit,SystolicBP,DiastolicBP,TimeTaken\n";
        foreach ($vitals as $vital) {
            $csv .= "{$vital->VitalID},{$vital->vital_type},{$vital->value},{$vital->unit},{$vital->SystolicBP},{$vital->DiastolicBP},{$vital->time_taken}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="vitals-patient-' . $patientId . '-' . date('Y-m-d') . '.csv"');
    }

    // ============ PROFILE ============
    public function editProfile()
    {
        $user = DB::select('SELECT * FROM [USER] WHERE userID = ?', [auth()->id()]);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return view('nurse.profile.edit', ['user' => $user[0]]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|digits:11',
            'password' => 'nullable|string|min:6',
        ]);

        $password = $validated['password'] ? bcrypt($validated['password']) : null;

        DB::statement("EXEC sp_update_user_profile
            @userID = " . auth()->id() . ",
            @first_name = N'" . str_replace("'", "''", $validated['first_name']) . "',
            @last_name = N'" . str_replace("'", "''", $validated['last_name']) . "',
            @email = N'" . str_replace("'", "''", $validated['email']) . "',
            @contact_number = N'" . str_replace("'", "''", $validated['contact_number']) . "'" .
            ($password ? ", @password = N'" . str_replace("'", "''", $password) . "'" : "")
        );

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
