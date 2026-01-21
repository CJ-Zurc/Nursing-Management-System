@extends('layouts.app')

@section('page-title', 'Patients - My Ward')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">My Patients</h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Patient List ({{ count($patients) }})</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('nurse.patients') }}" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by ID, name, or blood type..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('nurse.patients') }}" class="btn btn-secondary">Clear</a>
                    @endif
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="patientsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="max-width: 80px;">ID</th>
                            <th style="max-width: 180px;">Name</th>
                            <th style="max-width: 80px;">Age</th>
                            <th style="max-width: 100px;">Room/Bed</th>
                            <th style="max-width: 100px;">Blood Type</th>
                            <th style="max-width: 100px;">Status</th>
                            <th style="max-width: 180px;">Physician</th>
                            <th style="max-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('nurse.patient.show', $patient->PatientID) }}'">
                                <td style="max-width: 80px;"><strong>#{{ $patient->PatientID }}</strong></td>
                                <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $patient->first_name }} {{ $patient->last_name }}">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </td>
                                <td style="max-width: 80px;">
                                    @php
                                        $age = \Carbon\Carbon::parse($patient->dateOfBirth)->age;
                                    @endphp
                                    {{ $age }} yrs
                                </td>
                                <td style="max-width: 100px;">
                                    {{ $patient->roomNumber }}-{{ $patient->bedNumber }}  
                                </td>
                                <td style="max-width: 100px;">{{ $patient->blood_type }}</td>
                                <td style="max-width: 100px;">
                                    @if($patient->patient_status === 'Admitted')
                                        <span class="badge bg-success">Admitted</span>
                                    @else
                                        <span class="badge bg-warning">{{ $patient->patient_status }}</span>
                                    @endif
                                </td>
                                <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $patient->attending_physician ?? '-' }}">{{ $patient->attending_physician ?? '-' }}</td>
                                <td style="max-width: 150px;" onclick="event.stopPropagation();">
                                    <a href="{{ route('nurse.patient.show', $patient->PatientID) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View Profile
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No patients assigned to this ward</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
