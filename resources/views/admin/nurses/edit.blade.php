@extends('layouts.app')

@section('page-title', 'Edit Nurse')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Edit Nurse: {{ $nurse->first_name }} {{ $nurse->last_name }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nurse Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.nurse.update', $nurse->userID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ $nurse->first_name }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ $nurse->last_name }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $nurse->email }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number (11 digits) *</label>
                                <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ $nurse->contact_number }}" maxlength="11" pattern="\d{11}" required>
                                @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">License Number *</label>
                                <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ $nurse->license_number }}" required>
                                @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assigned Ward *</label>
                                <select name="WardID" class="form-select @error('WardID') is-invalid @enderror" required>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->WardID }}" {{ $nurse->WardID == $ward->WardID ? 'selected' : '' }}>
                                            {{ $ward->WardName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('WardID') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="Nurse" {{ $nurse->role === 'Nurse' ? 'selected' : '' }}>Nurse</option>
                                <option value="Senior Nurse" {{ $nurse->role === 'Senior Nurse' ? 'selected' : '' }}>Senior Nurse</option>
                                <option value="Charge Nurse" {{ $nurse->role === 'Charge Nurse' ? 'selected' : '' }}>Charge Nurse</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.nurses') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
