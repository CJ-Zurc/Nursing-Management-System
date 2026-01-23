@extends('layouts.app')

@section('page-title', 'Patient Profile')

@section('content')
<div class="container-fluid">
    <!-- Patient Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-2">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card"></i> ID: #{{ $patient->PatientID }} | 
                                <i class="fas fa-mars"></i> {{ $patient->Sex }} | 
                                <i class="fas fa-birthday-cake"></i> {{ $patient->dateOfBirth }}
                            </p>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $patient->WardName }}</span>
                                <span class="badge bg-success">{{ $patient->patient_status }}</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <strong>Room:</strong> {{ $patient->roomNumber }} | <strong>Bed:</strong> {{ $patient->bedNumber }}
                            </div>
                            <div class="mb-2">
                                <strong>Blood Type:</strong> {{ $patient->blood_type }}
                            </div>
                            <div>
                                <strong>Height:</strong> {{ $patient->height }}cm | <strong>Weight:</strong> {{ $patient->weight }}kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">Overview</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="allergies-tab" data-bs-toggle="tab" data-bs-target="#allergies" type="button">Allergies</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="conditions-tab" data-bs-toggle="tab" data-bs-target="#conditions" type="button">Conditions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="medications-tab" data-bs-toggle="tab" data-bs-target="#medications" type="button">Medications</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button">Vital Signs</button>
        </li>
        <li class="nav-item" role="presentation">
            <!-- Charts is a mediator (holds ChartID) and not an input view; removed from tabs -->
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button">Notes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="diagnosis-tab" data-bs-toggle="tab" data-bs-target="#diagnosis" type="button">Diagnosis</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Contact:</th>
                                    <td>{{ $patient->contact_Number }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $patient->address }}</td>
                                </tr>
                                <tr>
                                    <th>Guardian:</th>
                                    <td>{{ $patient->Guardian }}</td>
                                </tr>
                                <tr>
                                    <th>Guardian Contact:</th>
                                    <td>{{ $patient->guardian_Number }}</td>
                                </tr>
                                <tr>
                                    <th>Admission Date:</th>
                                    <td>{{ $patient->admission_date }}</td>
                                </tr>
                                <tr>
                                    <th>Attending Physician:</th>
                                    <td>{{ $patient->attending_physician }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Vital Statistics</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Height:</th>
                                    <td>{{ $patient->height }} cm</td>
                                </tr>
                                <tr>
                                    <th>Weight:</th>
                                    <td>{{ $patient->weight }} kg</td>
                                </tr>
                                <tr>
                                    <th>BMI:</th>
                                    <td>
                                        @php
                                            $bmi = $patient->weight / (($patient->height/100) ** 2);
                                            echo number_format($bmi, 2);
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th>Blood Type:</th>
                                    <td>{{ $patient->blood_type }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Allergies Tab -->
        <div class="tab-pane fade" id="allergies" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Patient Allergies</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#allergyModal">
                        <i class="fas fa-plus"></i> Add Allergy
                    </button>
                </div>
                <div class="card-body">
                    @if(count($allergies) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Allergen</th>
                                        <th>Reaction</th>
                                        <th>Severity</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allergies as $allergy)
                                        <tr>
                                            <td><strong>{{ $allergy->allergen }}</strong></td>
                                            <td>{{ $allergy->reaction }}</td>
                                            <td>
                                                @if($allergy->severity === 'Severe')
                                                    <span class="badge bg-danger">{{ $allergy->severity }}</span>
                                                @elseif($allergy->severity === 'Moderate')
                                                    <span class="badge bg-warning">{{ $allergy->severity }}</span>
                                                @else
                                                    <span class="badge bg-info">{{ $allergy->severity }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $allergy->allergy_type }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editAllergy({{ $allergy->AllergyID }}, '{{ $allergy->allergen }}', '{{ $allergy->reaction }}', '{{ $allergy->severity }}', '{{ $allergy->allergy_type }}')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('nurse.allergy.delete', [$allergy->AllergyID, $patient->PatientID]) }}" 
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No allergies recorded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Conditions Tab -->
        <div class="tab-pane fade" id="conditions" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Conditions at Birth / Medical Conditions</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#conditionModal">
                        <i class="fas fa-plus"></i> Add Condition
                    </button>
                </div>
                <div class="card-body">
                    @if(count($conditions) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Condition Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conditions as $condition)
                                        <tr>
                                            <td><strong>{{ $condition->condition_name }}</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $condition->status }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editCondition({{ $condition->ConditionID }}, '{{ $condition->condition_name }}', '{{ $condition->status }}')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('nurse.condition.delete', [$condition->ConditionID, $patient->PatientID]) }}" 
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No conditions recorded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medications Tab -->
        <div class="tab-pane fade" id="medications" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Medications & Inventory</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#medicationModal">
                        <i class="fas fa-plus"></i> Add Medication
                    </button>
                </div>
                <div class="card-body">
                    @if(count($medications) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Quantity</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medications as $med)
                                        <tr>
                                            <td>
                                                <strong>{{ $med->medicine_name }}</strong>
                                                <br>
                                                <small>{{ $med->medicine_notes }}</small>
                                            </td>
                                            <td>{{ $med->quantity }}</td>
                                            <td>
                                                @if($med->expiry_dates)
                                                    {{ \Carbon\Carbon::parse($med->expiry_dates)->format('Y-m-d') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($med->statuses === 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning">{{ $med->statuses }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                        data-bs-target="#scheduleModal" 
                                                        onclick="setMedication({{ $med->MedID }}, '{{ $med->medicine_name }}')">
                                                    <i class="fas fa-calendar"></i> Schedule
                                                </button>
                                                <a href="{{ route('nurse.medschedule.show', $med->MedID) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-syringe"></i> Administer
                                                </a>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                        data-bs-target="#quantityModal" 
                                                        onclick="setQuantityMedication({{ $med->MedID }}, '{{ $med->medicine_name }}', {{ $med->quantity }})">
                                                    <i class="fas fa-cubes"></i> Restock
                                                </button>
                                                <form action="{{ route('nurse.medication.delete', [$med->MedID, $patient->PatientID]) }}" 
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No medications added</p>
                    @endif
                </div>
            </div>
        </div>


        <!-- Notes Tab -->
        <div class="tab-pane fade" id="notes" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Clinical Notes</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#noteModal">
                        <i class="fas fa-plus"></i> Add Note
                    </button>
                </div>
                <div class="card-body">
                    @if(count($notes) > 0)
                        <div class="space-y-3">
                            @foreach($notes as $note)
                                <div class="card border-left-4" style="border-left: 4px solid @if($note->note_priority === 'High') #dc3545 @elseif($note->note_priority === 'Medium') #ffc107 @else #28a745 @endif;">
                                    <div class="card-body pb-2">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="card-title mb-1">{{ $note->note_title }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user"></i> {{ $note->NurseName }} | 
                                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($note->time_noted)->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                            <div>
                                                <span class="badge @if($note->note_priority === 'High') bg-danger @elseif($note->note_priority === 'Medium') bg-warning @else bg-success @endif me-2">
                                                    {{ $note->note_priority }}
                                                </span>
                                                <button class="btn btn-sm btn-primary" onclick="editNote({{ $note->NotesID }}, '{{ addslashes($note->note_title) }}', '{{ addslashes($note->note_description) }}', '{{ $note->note_priority }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('nurse.note.delete', [$note->NotesID, $patient->PatientID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="card-text mb-0">{{ $note->note_description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No notes recorded yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Diagnosis Tab -->
        <div class="tab-pane fade" id="diagnosis" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Diagnoses</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#diagnosisModal">
                        <i class="fas fa-plus"></i> Add Diagnosis
                    </button>
                </div>
                <div class="card-body">
                    @if(count($diagnoses) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="max-width: 150px;">Diagnosis</th>
                                        <th style="max-width: 250px;">Description</th>
                                        <th style="max-width: 100px;">Status</th>
                                        <th style="max-width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diagnoses as $diag)
                                        <tr>
                                            <td style="max-width: 150px;"><strong>{{ $diag->diagnosisName }}</strong></td>
                                            <td style="max-width: 250px;">{{ $diag->descriptions }}</td>
                                            <td style="max-width: 100px;">
                                                <span class="badge @if($diag->status === 'Active') bg-danger @elseif($diag->status === 'Resolved') bg-success @else bg-secondary @endif">
                                                    {{ $diag->status }}
                                                </span>
                                            </td>
                                            <td style="max-width: 120px;">
                                                <button class="btn btn-sm btn-primary" onclick="editDiagnosis({{ $diag->DiagID }}, '{{ addslashes($diag->diagnosisName) }}', '{{ addslashes($diag->descriptions) }}', '{{ $diag->status }}')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('nurse.diagnosis.delete', [$diag->DiagID, $patient->PatientID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No diagnoses recorded yet</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- Vital Signs Tab -->
        <div class="tab-pane fade" id="vitals" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vital Signs Records</h5>
                    <div>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#vitalSignModal">
                            <i class="fas fa-plus"></i> Record Vital Sign
                        </button>
                        @if(count($vitalSigns) > 0)
                            <a href="{{ route('nurse.vitals.export', $patient->PatientID) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i> Export
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(count($vitalSigns) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="max-width: 150px;">Date & Time</th>
                                        <th style="max-width: 120px;">Vital Type</th>
                                        <th style="max-width: 100px;">Value</th>
                                        <th style="max-width: 80px;">Unit</th>                                
                                        <th style="max-width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vitalSigns as $vital)
                                        <tr>
                                            <td style="max-width: 150px;">
                                                <small>{{ \Carbon\Carbon::parse($vital->time_taken)->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td style="max-width: 120px;">{{ $vital->vital_type }}</td>
                                            <td style="max-width: 100px;"><strong>{{ $vital->value }}</strong></td>
                                            <td style="max-width: 80px;">{{ $vital->unit }}</td>


                                            <td style="max-width: 120px;">
                                                <button class="btn btn-sm btn-primary" onclick="editVital({{ $vital->VitalID }}, '{{ $vital->vital_type }}', {{ $vital->value }}, '{{ $vital->unit }}', {{ $vital->SystolicBP ?? 'null' }}, {{ $vital->DiastolicBP ?? 'null' }})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('nurse.vital.delete', [$vital->VitalID, $patient->PatientID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No vital signs recorded yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Allergy Modal -->
<div class="modal fade" id="allergyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Allergy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.allergy.store', $patient->PatientID) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Allergen *</label>
                        <input type="text" name="allergen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reaction *</label>
                        <input type="text" name="reaction" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Severity *</label>
                        <select name="severity" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <input type="text" name="allergy_type" class="form-control" placeholder="e.g., Food, Drug, Environmental" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Allergy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Condition Modal -->
<div class="modal fade" id="conditionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Condition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.condition.store', $patient->PatientID) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Condition Name *</label>
                        <input type="text" name="condition_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Resolved">Resolved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Condition</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Medication Modal -->
<div class="modal fade" id="medicationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Medication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.medication.store', $patient->PatientID) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine Name *</label>
                        <input type="text" name="medicine_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date *</label>
                        <input type="date" name="expiry_dates" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="statuses" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="medicine_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Medication</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Modal (Placeholder - will be populated by JS) -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Medication Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quantityForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine *</label>
                        <input type="text" id="quantityMedName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Quantity *</label>
                        <input type="text" id="currentQuantity" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Action *</label>
                        <select name="action" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="add">Add Quantity (Restocking)</option>
                            <option value="subtract">Subtract Quantity</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Change (units) *</label>
                        <input type="number" name="quantity_change" class="form-control" min="1" required>
                        <small class="text-muted">How many units to add or subtract</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Quantity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Modal (Placeholder - will be populated by JS) -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Medication Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine *</label>
                        <input type="text" id="medName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dosage Amount (units) *</label>
                        <input type="number" name="dosage_amount" class="form-control" min="1" required>
                        <small class="text-muted">How many units to administer each time</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frequency (times per day) *</label>
                        <input type="number" name="frequency" class="form-control" min="1" max="24" required>
                        <small class="text-muted">Number of times to administer per day</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


<!-- Vital Sign Modal -->
<div class="modal fade" id="vitalSignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Multiple Vital Signs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.vital.store', ['chartId' => $charts[0]->ChartID ?? 0, 'patientId' => $patient->PatientID]) }}" method="POST" id="multiVitalForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Temperature -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-thermometer-half"></i> Temperature</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <input type="number" name="temperature" step="0.1" class="form-control form-control-sm" placeholder="Enter value" min="30" max="45">
                                    </div>
                                    <small class="text-muted">Unit: °C</small>
                                </div>
                            </div>
                        </div>

                        <!-- Heart Rate -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-heart"></i> Heart Rate</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <input type="number" name="heart_rate" class="form-control form-control-sm" placeholder="Enter value" min="0" max="200">
                                    </div>
                                    <small class="text-muted">Unit: bpm</small>
                                </div>
                            </div>
                        </div>

                        <!-- Blood Pressure -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-tint"></i> Blood Pressure</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="systolic_bp" class="form-control" placeholder="Systolic" min="0" max="300">
                                            <span class="input-group-text">/</span>
                                            <input type="number" name="diastolic_bp" class="form-control" placeholder="Diastolic" min="0" max="200">
                                        </div>
                                    </div>
                                    <small class="text-muted">Unit: mmHg</small>
                                </div>
                            </div>
                        </div>

                        <!-- Respiratory Rate -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-wind"></i> Respiratory Rate</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <input type="number" name="respiratory_rate" class="form-control form-control-sm" placeholder="Enter value" min="0" max="60">
                                    </div>
                                    <small class="text-muted">Unit: breaths/min</small>
                                </div>
                            </div>
                        </div>

                        <!-- Oxygen Saturation -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-lungs"></i> Oxygen Saturation</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <input type="number" name="oxygen_sat" step="0.1" class="form-control form-control-sm" placeholder="Enter value" min="0" max="100">
                                    </div>
                                    <small class="text-muted">Unit: %</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <small><i class="fas fa-info-circle"></i> Enter values for the vital signs you want to record. Leave blank for any you don't need to record.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record All Vital Signs</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Clinical Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.note.store', $charts[0]->ChartID ?? 0) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Note Title *</label>
                        <input type="text" name="note_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note Description *</label>
                        <textarea name="note_description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority *</label>
                        <select name="note_priority" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Diagnosis Modal -->
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('nurse.diagnosis.store', $charts[0]->ChartID ?? 0) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Diagnosis Name *</label>
                        <input type="text" name="diagnosisName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="descriptions" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Active">Active</option>
                            <option value="Resolved">Resolved</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Diagnosis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Allergy Modal -->
<div class="modal fade" id="editAllergyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Allergy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAllergyForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Allergen *</label>
                        <input type="text" id="editAllergen" name="allergen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reaction *</label>
                        <input type="text" id="editReaction" name="reaction" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Severity *</label>
                        <select id="editSeverity" name="severity" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <input type="text" id="editAllergyType" name="allergy_type" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Allergy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Condition Modal -->
<div class="modal fade" id="editConditionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Condition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editConditionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Condition Name *</label>
                        <input type="text" id="editConditionName" name="condition_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select id="editConditionStatus" name="status" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Resolved">Resolved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Condition</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Vital Sign Modal -->
<div class="modal fade" id="editVitalSignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Vital Sign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editVitalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Vital Type *</label>
                        <input type="text" id="editVitalType" name="vital_type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value *</label>
                        <input type="number" id="editVitalValue" name="value" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit *</label>
                        <input type="text" id="editVitalUnit" name="unit" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Systolic BP</label>
                        <input type="number" id="editSystolicBP" name="SystolicBP" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diastolic BP</label>
                        <input type="number" id="editDiastolicBP" name="DiastolicBP" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Vital Sign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" id="editNoteTitle" name="note_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea id="editNoteDescription" name="note_description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority *</label>
                        <select id="editNotePriority" name="note_priority" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Diagnosis Modal -->
<div class="modal fade" id="editDiagnosisModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDiagnosisForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Diagnosis Name *</label>
                        <input type="text" id="editDiagnosisName" name="diagnosisName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea id="editDiagnosisDescription" name="descriptions" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select id="editDiagnosisStatus" name="status" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Active">Active</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Under Review">Under Review</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Diagnosis</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function setMedication(medId, medName) {
        document.getElementById('medName').value = medName;
        document.getElementById('scheduleForm').action = `/nurse/medications/${medId}/schedule`;
    }

    function setQuantityMedication(medId, medName, currentQty) {
        document.getElementById('quantityMedName').value = medName;
        document.getElementById('currentQuantity').value = currentQty;
        document.getElementById('quantityForm').action = `/nurse/medications/${medId}/quantity`;
    }

    // Edit Allergy
    function editAllergy(allergyId, allergen, reaction, severity, allergyType) {
        document.getElementById('editAllergen').value = allergen;
        document.getElementById('editReaction').value = reaction;
        document.getElementById('editSeverity').value = severity;
        document.getElementById('editAllergyType').value = allergyType;
        document.getElementById('editAllergyForm').action = `/nurse/allergies/${allergyId}`;
        new bootstrap.Modal(document.getElementById('editAllergyModal')).show();
    }

    // Edit Condition
    function editCondition(conditionId, conditionName, status) {
        document.getElementById('editConditionName').value = conditionName;
        document.getElementById('editConditionStatus').value = status;
        document.getElementById('editConditionForm').action = `/nurse/conditions/${conditionId}`;
        new bootstrap.Modal(document.getElementById('editConditionModal')).show();
    }

    // Edit Vital Sign
    function editVital(vitalId, vitalType, value, unit, systolic, diastolic) {
        document.getElementById('editVitalType').value = vitalType;
        document.getElementById('editVitalValue').value = value;
        document.getElementById('editVitalUnit').value = unit;
        document.getElementById('editSystolicBP').value = systolic || '';
        document.getElementById('editDiastolicBP').value = diastolic || '';
        document.getElementById('editVitalForm').action = `/nurse/vitals/${vitalId}`;
        new bootstrap.Modal(document.getElementById('editVitalSignModal')).show();
    }

    // Edit Note
    function editNote(noteId, title, description, priority) {
        document.getElementById('editNoteTitle').value = title;
        document.getElementById('editNoteDescription').value = description;
        document.getElementById('editNotePriority').value = priority;
        document.getElementById('editNoteForm').action = `/nurse/notes/${noteId}`;
        new bootstrap.Modal(document.getElementById('editNoteModal')).show();
    }

    // Edit Diagnosis
    function editDiagnosis(diagnosisId, diagnosisName, description, status) {
        document.getElementById('editDiagnosisName').value = diagnosisName;
        document.getElementById('editDiagnosisDescription').value = description;
        document.getElementById('editDiagnosisStatus').value = status;
        document.getElementById('editDiagnosisForm').action = `/nurse/diagnosis/${diagnosisId}`;
        new bootstrap.Modal(document.getElementById('editDiagnosisModal')).show();
    }

</script>
@endsection
