@extends('layouts.app')

@section('page-title', 'Nurse Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">My Ward</h6>
                    <h3 class="mb-0">{{ $nurse->WardName }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">Assigned Patients</h6>
                    <h3 class="mb-0">{{ $patientCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">License</h6>
                    <h5 class="mb-0">{{ $nurse->license_number }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">Role</h6>
                    <h5 class="mb-0">{{ $nurse->role }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('nurse.patients') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> View Patients
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Patients -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patients ({{ $patientCount }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Room/Bed</th>
                                    <th>Status</th>
                                    <th>Admission Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                        <td>{{ $patient->roomNumber }} - {{ $patient->bedNumber }}</td>
                                        <td><span class="badge bg-success">{{ $patient->patient_status }}</span></td>
                                        <td>{{ $patient->admission_date }}</td>
                                        <td>
                                            <a href="{{ route('nurse.patient.show', $patient->PatientID) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No patients assigned</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Action</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->PatientName }}</td>
                                        <td>{{ $log->actions }}</td>
                                        <td>{{ $log->logtime }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No activity yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
