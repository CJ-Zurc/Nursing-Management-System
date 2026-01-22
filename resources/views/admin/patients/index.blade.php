@extends('layouts.app')

@section('page-title', 'Patient Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Patient Management</h2>
                <a href="{{ route('admin.patient.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Patient
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Patients</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('admin.patients') }}" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, ID, or phone..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.patients') }}" class="btn btn-secondary">Clear</a>
                    @endif
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="patientsTable">
                    <thead class="table-light">
                        <tr>
                        
                            <th style="max-width: 200px;">Name</th>
                            <th style="max-width: 120px;">Ward</th>
                            <th style="max-width: 100px;">Room/Bed</th>
                            <th style="max-width: 130px;">Phone</th>
                            <th style="max-width: 100px;">Status</th>
                            <th style="max-width: 120px;">Admission</th>
                            <th style="max-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.patient.edit', $patient->PatientID) }}'">
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $patient->first_name }} {{ $patient->last_name }}">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td style="max-width: 120px;"><span class="badge bg-info">{{ $patient->WardName }}</span></td>
                                <td style="max-width: 100px;">{{ $patient->roomNumber }}-{{ $patient->bedNumber }}</td>
                                <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $patient->contact_Number }}</td>
                                <td style="max-width: 100px;">
                                    @if($patient->patient_status === 'Admitted')
                                        <span class="badge bg-success">Admitted</span>
                                    @else
                                        <span class="badge bg-warning">{{ $patient->patient_status }}</span>
                                    @endif
                                </td>
                                <td style="max-width: 120px;">{{ $patient->admission_date }}</td>
                                <td style="max-width: 150px;" onclick="event.stopPropagation();">
                                    @if($patient->patient_status !== 'Discharged')
                                        <form action="{{ route('admin.patient.discharge', $patient->PatientID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Discharge this patient?')">
                                                <i class="fas fa-sign-out-alt"></i> Discharge
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">No patients found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
