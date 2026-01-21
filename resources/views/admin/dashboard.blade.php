@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Admin Dashboard</h1>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">Total Active Patients</h6>
                    <h3 class="mb-0">{{ $totalPatients ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title mb-2">Admitted Today</h6>
                    <h3 class="mb-0">{{ count($admissions) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Ward Distribution -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ward Capacity & Occupancy</h5>
                </div>
                <div class="card-body">
                    <canvas id="wardChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Admissions</h5>
                </div>
                <div class="card-body">
                    <canvas id="admissionsChart" height="150"></canvas>
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
                    <a href="{{ route('admin.patient.create') }}" class="btn btn-primary">+ Add Patient</a>
                    <a href="{{ route('admin.nurse.create') }}" class="btn btn-info">+ Add Nurse</a>
                    <a href="{{ route('admin.patients') }}" class="btn btn-secondary">Manage Patients</a>
                    <a href="{{ route('admin.nurses') }}" class="btn btn-secondary">Manage Nurses</a>
                    <a href="{{ route('admin.logs') }}" class="btn btn-warning">View Logs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Admissions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Admissions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Ward</th>
                                    <th>Room</th>
                                    <th>Admission Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admissions as $patient)
                                    <tr>
                                        <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                        <td><span class="badge bg-info">{{ $patient->WardName }}</span></td>
                                        <td>{{ $patient->roomNumber }} - {{ $patient->bedNumber }}</td>
                                        <td>{{ $patient->admission_date }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $patient->patient_status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.patient.edit', $patient->PatientID) }}" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent admissions</td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ward Distribution Chart - Showing Capacity (100) vs Occupied vs Remaining
    const wardChart = new Chart(document.getElementById('wardChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_map(fn($w) => $w->WardName, $wardStats)) !!},
            datasets: [{
                label: 'Occupied Beds',
                data: {!! json_encode(array_map(fn($w) => $w->occupied_beds ?? 0, $wardStats)) !!},
                backgroundColor: '#dc3545'
            }, {
                label: 'Remaining Capacity',
                data: {!! json_encode(array_map(fn($w) => $w->remaining_capacity ?? 0, $wardStats)) !!},
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            stacked: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Ward Capacity Overview'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value;
                        }
                    }
                }
            }
        }
    });

    // Admissions by Month Chart
    const admissionsChart = new Chart(document.getElementById('admissionsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(fn($a) => $a->month_name ?? 'N/A', $admissionTrends ?? [])) !!},
            datasets: [{
                label: 'Monthly Admissions',
                data: {!! json_encode(array_map(fn($a) => $a->count ?? 0, $admissionTrends ?? [])) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
