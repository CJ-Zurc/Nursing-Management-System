<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'Nurse') {
            return redirect()->route('nurse.dashboard');
        }
    }
    return redirect()->route('login');
})->name('home');

// Test route (remove after testing)
Route::get('/test-modal', function () {
    return view('test_modal');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Patient Management
    Route::get('/admin/patients', [AdminController::class, 'patients'])->name('admin.patients');
    Route::get('/admin/patients/create', [AdminController::class, 'createPatient'])->name('admin.patient.create');
    Route::post('/admin/patients', [AdminController::class, 'storePatient'])->name('admin.patient.store');
    Route::get('/admin/patients/{id}/edit', [AdminController::class, 'editPatient'])->name('admin.patient.edit');
    Route::put('/admin/patients/{id}', [AdminController::class, 'updatePatient'])->name('admin.patient.update');
    Route::post('/admin/patients/{id}/discharge', [AdminController::class, 'dischargePatient'])->name('admin.patient.discharge');

    // Nurse Management
    Route::get('/admin/nurses', [AdminController::class, 'nurses'])->name('admin.nurses');
    Route::get('/admin/nurses/create', [AdminController::class, 'createNurse'])->name('admin.nurse.create');
    Route::post('/admin/nurses', [AdminController::class, 'storeNurse'])->name('admin.nurse.store');
    Route::get('/admin/nurses/{id}/edit', [AdminController::class, 'editNurse'])->name('admin.nurse.edit');
    Route::put('/admin/nurses/{id}', [AdminController::class, 'updateNurse'])->name('admin.nurse.update');
    Route::post('/admin/nurses/{id}/deactivate', [AdminController::class, 'deactivateNurse'])->name('admin.nurse.deactivate');

    // Logs
    Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::get('/admin/logs/export', [AdminController::class, 'exportLogs'])->name('admin.logs.export');
    
    // Profile
    Route::get('/admin/profile', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});

// Nurse routes
Route::middleware(['auth', 'role:Nurse'])->group(function () {
    // Dashboard
    Route::get('/nurse/dashboard', [NurseController::class, 'dashboard'])->name('nurse.dashboard');

    // Patient List
    Route::get('/nurse/patients', [NurseController::class, 'patients'])->name('nurse.patients');
    Route::get('/nurse/patients/{id}', [NurseController::class, 'showPatient'])->name('nurse.patient.show');

    // Allergies
    Route::post('/nurse/patients/{patientId}/allergies', [NurseController::class, 'storeAllergy'])->name('nurse.allergy.store');
    Route::put('/nurse/allergies/{allergyId}', [NurseController::class, 'updateAllergy'])->name('nurse.allergy.update');
    Route::delete('/nurse/allergies/{allergyId}/{patientId}', [NurseController::class, 'deleteAllergy'])->name('nurse.allergy.delete');

    // Conditions
    Route::post('/nurse/patients/{patientId}/conditions', [NurseController::class, 'storeCondition'])->name('nurse.condition.store');
    Route::put('/nurse/conditions/{conditionId}', [NurseController::class, 'updateCondition'])->name('nurse.condition.update');
    Route::delete('/nurse/conditions/{conditionId}/{patientId}', [NurseController::class, 'deleteCondition'])->name('nurse.condition.delete');

    // Medications
    Route::post('/nurse/patients/{patientId}/medications', [NurseController::class, 'storeMedication'])->name('nurse.medication.store');
    Route::put('/nurse/medications/{medId}', [NurseController::class, 'updateMedication'])->name('nurse.medication.update');
    Route::put('/nurse/medications/{medId}/quantity', [NurseController::class, 'updateMedicationQuantity'])->name('nurse.medication.quantity');
    Route::delete('/nurse/medications/{medId}/{patientId}', [NurseController::class, 'deleteMedication'])->name('nurse.medication.delete');

    // Medication Schedule
    Route::post('/nurse/medications/{medId}/schedule', [NurseController::class, 'storeMedicationSchedule'])->name('nurse.medschedule.store');
    Route::put('/nurse/medication-schedule/{schedId}', [NurseController::class, 'updateMedicationSchedule'])->name('nurse.medschedule.update');
    Route::get('/nurse/medications/{medId}/schedules', [NurseController::class, 'showMedicationSchedules'])->name('nurse.medschedule.show');
    Route::post('/nurse/medication-schedule/{schedId}/administer', [NurseController::class, 'administerMedication'])->name('nurse.medschedule.administer');

    // Charts
    Route::post('/nurse/patients/{patientId}/charts', [NurseController::class, 'createChart'])->name('nurse.chart.create');
    Route::get('/nurse/charts/{chartId}/patient/{patientId}', [NurseController::class, 'showChart'])->name('nurse.chart.show');

    // Vital Signs
    Route::post('/nurse/charts/{chartId}/vitals', [NurseController::class, 'storeVitalSign'])->name('nurse.vital.store');

    // Diagnosis
    Route::post('/nurse/charts/{chartId}/diagnosis', [NurseController::class, 'storeDiagnosis'])->name('nurse.diagnosis.store');

    // Notes
    Route::post('/nurse/charts/{chartId}/notes', [NurseController::class, 'storeNote'])->name('nurse.note.store');

    // Export
    Route::get('/nurse/charts/{chartId}/vitals/export', [NurseController::class, 'exportVitals'])->name('nurse.vitals.export');
    Route::get('/nurse/patients/{patientId}/vitals/export', [NurseController::class, 'exportPatientVitals'])->name('nurse.vitals.export');
    
    // Profile
    Route::get('/nurse/profile', [NurseController::class, 'editProfile'])->name('nurse.profile.edit');
    Route::put('/nurse/profile', [NurseController::class, 'updateProfile'])->name('nurse.profile.update');
});
