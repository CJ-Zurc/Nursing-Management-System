@extends('layouts.app')

@section('page-title', 'Edit Patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Edit Patient: {{ $patient->first_name }} {{ $patient->last_name }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.patient.update', $patient->PatientID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $patient->first_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $patient->last_name }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number (11 digits) *</label>
                                <input type="text" name="contact_Number" class="form-control" value="{{ $patient->contact_Number }}" maxlength="11" pattern="\d{11}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex *</label>
                                <select name="Sex" class="form-select" required>
                                    <option value="Male" {{ $patient->Sex === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $patient->Sex === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ $patient->Sex === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Height (cm) *</label>
                                <input type="number" name="height" step="0.1" class="form-control" value="{{ $patient->height }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weight (kg) *</label>
                                <input type="number" name="weight" step="0.1" class="form-control" value="{{ $patient->weight }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ward *</label>
                                <select name="WardID" class="form-select" required>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->WardID }}" {{ $patient->WardID == $ward->WardID ? 'selected' : '' }}>
                                            {{ $ward->WardName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Contact (11 digits)</label>
                                <input type="text" name="guardian_Number" class="form-control" value="{{ $patient->guardian_Number }}" maxlength="11" pattern="\d{11}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Number *</label>
                                <input type="text" name="roomNumber" class="form-control" value="{{ $patient->roomNumber }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bed Number *</label>
                                <input type="text" name="bedNumber" class="form-control" value="{{ $patient->bedNumber }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Patient Status *</label>
                            <select name="patient_status" class="form-select" required>
                                <option value="Admitted" {{ $patient->patient_status === 'Admitted' ? 'selected' : '' }}>Admitted</option>
                                <option value="Observation" {{ $patient->patient_status === 'Observation' ? 'selected' : '' }}>Observation</option>
                                <option value="Discharged" {{ $patient->patient_status === 'Discharged' ? 'selected' : '' }}>Discharged</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attending Physician</label>
                            <input type="text" name="attending_physician" class="form-control" value="{{ $patient->attending_physician }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Patient
                            </button>
                            <a href="{{ route('admin.patients') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
