@extends('layouts.app')

@section('page-title', 'Medication Schedules - Administer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Medication: {{ $medication->medicine_name }}</h2>
                    <p class="text-muted mb-0">Current Quantity: <strong>{{ $medication->quantity }}</strong></p>
                </div>
                <a href="{{ route('nurse.patient.show', $medication->PatientID) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Patient
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Medication Schedules</h5>
        </div>
        <div class="card-body">
            @if(count($schedules) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Dosage Amount</th>
                                <th>Frequency (times/day)</th>
                                <th>Status</th>
                                <th>Last Administered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <strong>{{ $schedule->dosage_amount }}</strong> 
                                        <small class="text-muted">(units)</small>
                                    </td>
                                    <td>{{ $schedule->frequency }}x daily</td>
                                    <td>
                                        @if($schedule->taken_status === 'Administered')
                                            <span class="badge bg-success">Administered</span>
                                        @elseif($schedule->taken_status === 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-info">{{ $schedule->taken_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($schedule->last_administered)
                                            {{ \Carbon\Carbon::parse($schedule->last_administered)->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <!-- Next Scheduled column removed per user request -->
                                    <td>
                                        @if($schedule->taken_status !== 'Administered')
                                            <form action="{{ route('nurse.medschedule.administer', $schedule->SchedID) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Administer {{ $medication->medicine_name }} - {{ $schedule->dosage_amount }} units?\n\nThis will reduce the quantity by {{ $schedule->dosage_amount }}.')">
                                                    <i class="fas fa-check"></i> Administer
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Already Given</span>
                                        @endif
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editScheduleModal{{ $schedule->SchedID }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No schedules have been created for this medication yet.
                    <a href="{{ route('nurse.patient.show', $medication->PatientID) }}" class="alert-link">Create a schedule</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Schedule Modals -->
    @foreach($schedules as $schedule)
    <div class="modal fade" id="editScheduleModal{{ $schedule->SchedID }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('nurse.medschedule.update', $schedule->SchedID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Dosage Amount *</label>
                            <input type="number" name="dosage_amount" class="form-control" value="{{ $schedule->dosage_amount }}" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frequency (times per day) *</label>
                            <input type="number" name="frequency" class="form-control" value="{{ $schedule->frequency }}" min="1" max="24" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="taken_status" class="form-select">
                                <option value="Pending" {{ $schedule->taken_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Administered" {{ $schedule->taken_status === 'Administered' ? 'selected' : '' }}>Administered</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
