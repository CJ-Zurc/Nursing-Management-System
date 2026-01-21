@extends('layouts.app')

@section('page-title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Activity Logs</h2>
                <a href="{{ route('admin.logs.export') }}" class="btn btn-info">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Logs</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.logs') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Logs ({{ count($logs) }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="max-width: 150px;">Date & Time</th>
                            <th style="max-width: 150px;">User</th>
                            <th style="max-width: 180px;">Patient</th>
                            <th style="max-width: 150px;">Action</th>
                            <th style="max-width: 250px;">Description</th>
                            <th style="max-width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td style="max-width: 150px;"><small>{{ $log->logtime }}</small></td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->UserName }}"><strong>{{ $log->UserName }}</strong></td>
                                <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->PatientName }}">{{ $log->PatientName }}</td>
                                <td style="max-width: 150px;">
                                    <span class="badge" style="background-color: 
                                        @if(strpos($log->actions, 'Create') !== false || strpos($log->actions, 'Add') !== false) #28a745
                                        @elseif(strpos($log->actions, 'Update') !== false) #28a745
                                        @elseif(strpos($log->actions, 'Discharge') !== false || strpos($log->actions, 'Remove') !== false || strpos($log->actions, 'Decrement') !== false) #dc3545
                                        @else #007bff
                                        @endif
                                    ">{{ $log->actions }}</span>
                                </td>
                                <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->descriptions }}">{{ $log->descriptions }}</td>
                                <td style="max-width: 100px;">
                                    @if($log->log_status === 'Success')
                                        <span class="badge bg-success">{{ $log->log_status }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $log->log_status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No activity logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
