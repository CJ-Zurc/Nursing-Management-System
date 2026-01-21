@extends('layouts.app')

@section('page-title', 'Patient Chart')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-medical"></i> Chart #{{ $chart->ChartID }}</h2>
                <a href="{{ route('nurse.vitals.export', $chart->ChartID) }}" class="btn btn-info">
                    <i class="fas fa-download"></i> Export Vitals
                </a>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vitals-tab" type="button">
                <i class="fas fa-heartbeat"></i> Vital Signs
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#diagnosis-tab" type="button">
                <i class="fas fa-stethoscope"></i> Diagnosis
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notes-tab" type="button">
                <i class="fas fa-sticky-note"></i> Notes
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- VITAL SIGNS TAB -->
        <div class="tab-pane fade show active" id="vitals-tab">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-heartbeat"></i> Vital Signs Record</h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addVitalModal">
                        <i class="fas fa-plus"></i> Add Vital
                    </button>
                </div>
                <div class="card-body">
                    @if(count($vitalSigns) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-thermometer-half"></i> Type</th>
                                        <th>Value</th>
                                        <th>Unit</th>
                                        <th>Systolic</th>
                                        <th>Diastolic</th>
                                        <th><i class="fas fa-clock"></i> Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vitalSigns as $vital)
                                        <tr>
                                            <td><strong>{{ $vital->vital_type }}</strong></td>
                                            <td>{{ $vital->value }}</td>
                                            <td>{{ $vital->unit }}</td>
                                            <td>{{ $vital->SystolicBP ?? '-' }}</td>
                                            <td>{{ $vital->DiastolicBP ?? '-' }}</td>
                                            <td><small class="text-muted">{{ $vital->time_taken }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p>No vital signs recorded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- DIAGNOSIS TAB -->
        <div class="tab-pane fade" id="diagnosis-tab">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-stethoscope"></i> Diagnosis Record</h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addDiagnosisModal">
                        <i class="fas fa-plus"></i> Add Diagnosis
                    </button>
                </div>
                <div class="card-body">
                    @if(count($diagnoses) > 0)
                        <div class="row">
                            @foreach($diagnoses as $diag)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-warning h-100">
                                        <div class="card-body">
                                            <h6 class="card-title text-warning">{{ $diag->diagnosisName }}</h6>
                                            <p class="card-text small">{{ $diag->descriptions }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-secondary">{{ $diag->status }}</span>
                                                </div>
                                                <small class="text-muted">{{ $diag->date_recorded }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p>No diagnoses recorded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- NOTES TAB -->
        <div class="tab-pane fade" id="notes-tab">
            <div class="card border-info">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Nurse Notes (Personal Diary)</h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                        <i class="fas fa-plus"></i> Add Note
                    </button>
                </div>
                <div class="card-body">
                    @if(count($notes) > 0)
                        <div class="timeline">
                            @foreach($notes as $note)
                                <div class="timeline-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $note->note_title }}</h6>
                                            <small class="text-muted">By {{ $note->NurseName }}</small>
                                        </div>
                                        <div>
                                            @if($note->note_priority === 'High')
                                                <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> High</span>
                                            @elseif($note->note_priority === 'Medium')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-triangle-exclamation"></i> Medium</span>
                                            @else
                                                <span class="badge bg-success"><i class="fas fa-circle-check"></i> Low</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="card-text mb-2">{{ $note->note_description }}</p>
                                    <small class="text-muted"><i class="fas fa-clock"></i> {{ $note->time_noted }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p>No notes added yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================== MODALS ================== -->
<!-- VITAL SIGN MODAL -->
<div class="modal fade" id="addVitalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- modal header + form -->
        </div>
    </div>
</div>

<!-- DIAGNOSIS MODAL -->
<div class="modal fade" id="addDiagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-stethoscope"></i> Record Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('nurse.diagnosis.store', $chart->ChartID) }}">
                @csrf
                <div class="modal-body">
                    <!-- form fields -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- NOTES MODAL -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-sticky-note"></i> Add Nurse Note</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('nurse.note.store', $chart->ChartID) }}">
                @csrf
                <div class="modal-body">
                    <!-- form fields -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<style>
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .timeline-item {
        padding-left: 0;
    }

    .timeline-item:last-child {
        border-bottom: none !important;
    }

    .nav-tabs .nav-link {
        color: #333;
    }

    .nav-tabs .nav-link i {
        margin-right: 5px;
    }

    .card-header i {
        margin-right: 8px;
    }
</style>
@endsection
