@extends('layouts.app')

@section('page-title', 'Nurse Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Nurse Management</h2>
                <a href="{{ route('admin.nurse.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Nurse
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Nurses</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or email...">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="nursesTable">
                    <thead class="table-light">
                        <tr>
                            <th style="max-width: 180px;">Name</th>
                            <th style="max-width: 200px;">Email</th>
                            <th style="max-width: 150px;">License</th>
                            <th style="max-width: 120px;">Ward</th>
                            <th style="max-width: 100px;">Role</th>
                            <th style="max-width: 130px;">Contact</th>
                            <th style="max-width: 100px;">Status</th>
                            <th style="max-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nurses as $nurse)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.nurse.edit', $nurse->userID) }}'">
                                <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $nurse->first_name }} {{ $nurse->last_name }}"><strong>{{ $nurse->first_name }} {{ $nurse->last_name }}</strong></td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $nurse->email }}">{{ $nurse->email }}</td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $nurse->license_number }}</td>
                                <td style="max-width: 120px;"><span class="badge bg-info">{{ $nurse->WardName }}</span></td>
                                <td style="max-width: 100px;">{{ $nurse->role }}</td>
                                <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $nurse->contact_number }}</td>
                                <td style="max-width: 100px;">
                                    @if($nurse->nurseActive)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td style="max-width: 150px;" onclick="event.stopPropagation();">
                                    @if($nurse->nurseActive)
                                        <form action="{{ route('admin.nurse.deactivate', $nurse->userID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deactivate this nurse account?')">
                                                <i class="fas fa-user-times"></i> Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">No nurses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.getElementById('nursesTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endsection
