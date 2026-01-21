@extends('layouts.app')

@section('page-title', 'Create Patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Create New Patient</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.patient.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" name="dateOfBirth" class="form-control @error('dateOfBirth') is-invalid @enderror" value="{{ old('dateOfBirth') }}" required>
                                @error('dateOfBirth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex *</label>
                                <select name="Sex" class="form-select @error('Sex') is-invalid @enderror" required>
                                    <option value="">Select...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('Sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number (11 digits) *</label>
                                <input type="text" name="contact_Number" class="form-control @error('contact_Number') is-invalid @enderror" value="{{ old('contact_Number') }}" maxlength="11" required pattern="\d{11}">
                                @error('contact_Number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blood Type *</label>
                                <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror" required>
                                    <option value="">Select...</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                                @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Height (cm) *</label>
                                <input type="number" name="height" step="0.1" class="form-control @error('height') is-invalid @enderror" value="{{ old('height') }}" required>
                                @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weight (kg) *</label>
                                <input type="number" name="weight" step="0.1" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}" required>
                                @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" name="Guardian" class="form-control" value="{{ old('Guardian') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Number *</label>
                                <input type="text" name="roomNumber" class="form-control @error('roomNumber') is-invalid @enderror" value="{{ old('roomNumber') }}" required>
                                @error('roomNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ward *</label>
                                <select name="WardID" class="form-select @error('WardID') is-invalid @enderror" required>
                                    <option value="">Select Ward...</option>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->WardID }}" {{ old('WardID') == $ward->WardID ? 'selected' : '' }}>
                                            {{ $ward->WardName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('WardID') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Contact (11 digits)</label>
                                <input type="text" name="guardian_Number" class="form-control @error('guardian_Number') is-invalid @enderror" value="{{ old('guardian_Number') }}" maxlength="11" pattern="\d{11}">
                                @error('guardian_Number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bed Number *</label>
                                <input type="text" name="bedNumber" class="form-control @error('bedNumber') is-invalid @enderror" value="{{ old('bedNumber') }}" required>
                                @error('bedNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Attending Physician</label>
                                <input type="text" name="attending_physician" class="form-control" value="{{ old('attending_physician') }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Patient
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
