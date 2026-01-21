<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        // Get admission and discharge data for today
        $today = Carbon::today();
        $admissions = DB::select('EXEC sp_read_today_admissions @Date = ?', [$today]);

        // Get ward capacity with active patient counts
        $wardStats = DB::select('EXEC sp_read_ward_capacity');

        // Count total active patients
        $totalPatientsResult = DB::select('EXEC sp_read_total_active_patients');
        $totalPatients = $totalPatientsResult[0]->total_patients ?? 0;

        // Get monthly admissions trends
        $admissionTrends = DB::select('EXEC sp_read_monthly_admissions');

        return view('admin.dashboard', [
            'admissions' => $admissions,
            'wardStats' => $wardStats,
            'totalPatients' => $totalPatients,
            'admissionTrends' => $admissionTrends,
        ]);
    }

    // ============ PATIENT MANAGEMENT ============
    public function patients(Request $request)
    {
        $search = $request->query('search', '');
        
        if ($search) {
            $patients = DB::select('SELECT * FROM [PATIENT] WHERE CAST(PatientID AS NVARCHAR(MAX)) LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR contact_Number LIKE ?', 
                ['%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
        } else {
            $patients = DB::select('EXEC sp_read_all_patients');
        }
        
        return view('admin.patients.index', ['patients' => $patients, 'search' => $search]);
    }

    public function createPatient()
    {
        $wards = DB::select('EXEC sp_read_all_wards');
        return view('admin.patients.create', ['wards' => $wards]);
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'WardID' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dateOfBirth' => 'required|date|before:today',
            'Sex' => 'required|string',
            'contact_Number' => 'required|digits:11',
            'Guardian' => 'nullable|string',
            'guardian_Number' => 'nullable|digits:11',
            'address' => 'nullable|string',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'blood_type' => 'nullable|string',
            'roomNumber' => 'required|string',
            'bedNumber' => 'required|string',
            'attending_physician' => 'nullable|string',
        ]);

        $result = DB::statement("EXEC sp_create_patient_record
            @WardID = " . $validated['WardID'] . ",
            @first_name = N'" . str_replace("'", "''", $validated['first_name']) . "',
            @last_name = N'" . str_replace("'", "''", $validated['last_name']) . "',
            @dateOfBirth = N'" . $validated['dateOfBirth'] . "',
            @Sex = N'" . $validated['Sex'] . "',
            @contact_Number = N'" . str_replace("'", "''", $validated['contact_Number']) . "',
            @Guardian = N'" . (isset($validated['Guardian']) ? str_replace("'", "''", $validated['Guardian']) : '') . "',
            @guardian_Number = N'" . (isset($validated['guardian_Number']) ? str_replace("'", "''", $validated['guardian_Number']) : '') . "',
            @address = N'" . (isset($validated['address']) ? str_replace("'", "''", $validated['address']) : '') . "',
            @height = " . $validated['height'] . ",
            @weight = " . $validated['weight'] . ",
            @blood_type = N'" . (isset($validated['blood_type']) ? $validated['blood_type'] : '') . "',
            @roomNumber = N'" . $validated['roomNumber'] . "',
            @bedNumber = N'" . $validated['bedNumber'] . "',
            @admission_date = N'" . date('Y-m-d') . "',
            @attending_physician = N'" . (isset($validated['attending_physician']) ? str_replace("'", "''", $validated['attending_physician']) : '') . "'
        ");

        return redirect()->route('admin.patients')->with('success', 'Patient created successfully');
    }

    public function editPatient($id)
    {
        $patient = DB::select('EXEC sp_read_patient_by_id @PatientID = ' . $id)[0] ?? null;
        $wards = DB::select('EXEC sp_read_all_wards');

        if (!$patient) {
            return redirect()->route('admin.patients')->with('error', 'Patient not found');
        }

        return view('admin.patients.edit', ['patient' => $patient, 'wards' => $wards]);
    }

    public function updatePatient(Request $request, $id)
    {
        $validated = $request->validate([
            'WardID' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dateOfBirth' => 'required|date|before:today',
            'Sex' => 'required|string',
            'contact_Number' => 'required|digits:11',
            'Guardian' => 'nullable|string',
            'guardian_Number' => 'nullable|digits:11',
            'address' => 'nullable|string',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'blood_type' => 'nullable|string',
            'roomNumber' => 'required|string',
            'bedNumber' => 'required|string',
            'attending_physician' => 'nullable|string',
            'patient_status' => 'required|string',
        ]);

        DB::statement("EXEC sp_update_patient_record
            @PatientID = " . $id . ",
            @WardID = " . $validated['WardID'] . ",
            @first_name = N'" . str_replace("'", "''", $validated['first_name']) . "',
            @last_name = N'" . str_replace("'", "''", $validated['last_name']) . "',
            @dateOfBirth = N'" . $validated['dateOfBirth'] . "',
            @Sex = N'" . $validated['Sex'] . "',
            @contact_Number = N'" . str_replace("'", "''", $validated['contact_Number']) . "',
            @Guardian = N'" . (isset($validated['Guardian']) ? str_replace("'", "''", $validated['Guardian']) : '') . "',
            @guardian_Number = N'" . (isset($validated['guardian_Number']) ? str_replace("'", "''", $validated['guardian_Number']) : '') . "',
            @address = N'" . (isset($validated['address']) ? str_replace("'", "''", $validated['address']) : '') . "',
            @height = " . $validated['height'] . ",
            @weight = " . $validated['weight'] . ",
            @blood_type = N'" . (isset($validated['blood_type']) ? $validated['blood_type'] : '') . "',
            @roomNumber = N'" . $validated['roomNumber'] . "',
            @bedNumber = N'" . $validated['bedNumber'] . "',
            @attending_physician = N'" . (isset($validated['attending_physician']) ? str_replace("'", "''", $validated['attending_physician']) : '') . "',
            @patient_status = N'" . $validated['patient_status'] . "'
        ");

        return redirect()->route('admin.patients')->with('success', 'Patient updated successfully');
    }

    public function dischargePatient($id)
    {
        DB::statement("EXEC sp_discharge_patient @PatientID = " . $id . ", @discharge_date = N'" . date('Y-m-d') . "'");

        // Get user's actual userID (could be Admin or Nurse)
        $userID = auth()->id();

        DB::statement("EXEC sp_create_log_record
            @userID = " . $userID . ",
            @PatientID = " . $id . ",
            @actions = N'Discharge',
            @descriptions = N'Patient discharged',
            @log_status = N'Success'
        ");

        return redirect()->route('admin.patients')->with('success', 'Patient discharged successfully');
    }

    // ============ NURSE MANAGEMENT ============
    public function nurses()
    {
        $nurses = DB::select('EXEC sp_read_all_nurses');
        return view('admin.nurses.index', ['nurses' => $nurses]);
    }

    public function createNurse()
    {
        $wards = DB::select('EXEC sp_read_all_wards');
        return view('admin.nurses.create', ['wards' => $wards]);
    }

    public function storeNurse(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:USER,email',
            'contact_number' => 'required|digits:11',
            'password' => 'required|string|min:6',
            'WardID' => 'required|integer',
            'license_number' => 'required|string',
            'role' => 'required|string',
        ]);

        $result = DB::statement("EXEC sp_create_nurse_account
            @first_name = N'" . str_replace("'", "''", $validated['first_name']) . "',
            @last_name = N'" . str_replace("'", "''", $validated['last_name']) . "',
            @contact_number = N'" . (isset($validated['contact_number']) ? str_replace("'", "''", $validated['contact_number']) : '') . "',
            @email = N'" . $validated['email'] . "',
            @password = N'" . bcrypt($validated['password']) . "',
            @WardID = " . $validated['WardID'] . ",
            @license_number = N'" . str_replace("'", "''", $validated['license_number']) . "',
            @role = N'" . str_replace("'", "''", $validated['role']) . "'
        ");

        return redirect()->route('admin.nurses')->with('success', 'Nurse account created successfully');
    }

    public function editNurse($id)
    {
        $nurse = DB::select('EXEC sp_read_nurse_by_id @userID = ' . $id);
        
        if (!$nurse) {
            return redirect()->route('admin.nurses')->with('error', 'Nurse not found');
        }

        $nurse = $nurse[0];
        $wards = DB::select('EXEC sp_read_all_wards');

        return view('admin.nurses.edit', ['nurse' => $nurse, 'wards' => $wards]);
    }

    public function updateNurse(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|digits:11',
            'WardID' => 'required|integer',
            'license_number' => 'required|string',
            'role' => 'required|string',
        ]);

        DB::statement("EXEC sp_update_nurse
            @userID = " . $id . ",
            @first_name = N'" . str_replace("'", "''", $validated['first_name']) . "',
            @last_name = N'" . str_replace("'", "''", $validated['last_name']) . "',
            @contact_number = N'" . str_replace("'", "''", $validated['contact_number']) . "',
            @email = N'" . str_replace("'", "''", $validated['email']) . "',
            @WardID = " . $validated['WardID'] . ",
            @license_number = N'" . str_replace("'", "''", $validated['license_number']) . "',
            @role = N'" . str_replace("'", "''", $validated['role']) . "'
        ");

        return redirect()->route('admin.nurses')->with('success', 'Nurse updated successfully');
    }

    public function deactivateNurse($id)
    {
        DB::statement('EXEC sp_deactivate_nurse @userID = ' . $id);

        DB::statement("EXEC sp_create_log_record
            @userID = " . auth()->id() . ",
            @PatientID = 0,
            @actions = N'Deactivate Nurse',
            @descriptions = N'Nurse account deactivated',
            @log_status = N'Success'
        ");

        return redirect()->route('admin.nurses')->with('success', 'Nurse deactivated successfully');
    }

    // ============ LOGS ============
    public function logs(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $logs = DB::select("EXEC sp_read_logs_by_date_range @StartDate = N'" . $request->start_date . "', @EndDate = N'" . $request->end_date . "'");
        } else {
            $logs = DB::select('EXEC sp_read_all_logs');
        }

        return view('admin.logs.index', ['logs' => $logs]);
    }

    public function exportLogs()
    {
        $logs = DB::select('EXEC sp_read_all_logs');
        
        $csv = "LogID,UserName,PatientName,Actions,Descriptions,Status,DateTime\n";
        foreach ($logs as $log) {
            $csv .= "{$log->LogID},{$log->UserName},{$log->PatientName},{$log->actions},{$log->descriptions},{$log->log_status},{$log->logtime}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="logs-' . date('Y-m-d') . '.csv"');
    }

    // ============ PROFILE ============
    public function editProfile()
    {
        $user = DB::select('SELECT * FROM [USER] WHERE userID = ?', [auth()->id()]);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return view('admin.profile.edit', ['user' => $user[0]]);
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
