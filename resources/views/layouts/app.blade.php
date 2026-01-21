<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nursing Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7fafc;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            padding-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .brand {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(0, 0, 0, 0.2);
            border-left: 4px solid white;
        }

        .main-content {
            margin-left: 250px;
            padding: 0;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f7fafc;
            border: none;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-body {
            padding: 20px;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .badge {
            padding: 5px 10px;
        }

        .table {
            margin-bottom: 0;
        }

        .table-hover tbody tr:hover {
            background-color: #f7fafc;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        @auth
        <div class="sidebar">
            <div class="brand">🏥 NMS</div>
            
            @if(auth()->user()->system_role === 'Admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.patients') }}" class="nav-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Patients
                </a>
                <a href="{{ route('admin.nurses') }}" class="nav-link {{ request()->routeIs('admin.nurses*') ? 'active' : '' }}">
                    <i class="fas fa-user-nurse"></i> Nurses
                </a>
                <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Activity Logs
                </a>
            @elseif(auth()->user()->system_role === 'Nurse')
                <a href="{{ route('nurse.dashboard') }}" class="nav-link {{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('nurse.patients') }}" class="nav-link {{ request()->routeIs('nurse.patients*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Patients
                </a>
            @endif

            <div style="position: absolute; bottom: 20px; width: 100%; padding: 0 20px;">
                <hr style="border-color: rgba(255, 255, 255, 0.2);">

                <a href="{{ route('logout') }}" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                 
                   <i class="fas fa-sign-out-alt" style="color: red;"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        @endif

        <!-- Main Content -->
         
        <div class="main-content" style="width: 100%;">
            @auth
            <div class="topbar">
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <div class="topbar-user">
                    <span>{{ auth()->user()->first_name }}</span>
                    <i class="fas fa-user-circle fa-2x"></i>
                </div>
            </div>
            @endif

            <div style="padding: 30px;">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        // Show alerts using SweetAlert
        @if(session('success'))
            Swal.fire({
                title: 'Success',
                text: '{{ session('success') }}',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Confirmation for delete/discharge actions
        function confirmAction(message = 'Are you sure?') {
            return new Promise((resolve) => {
                Swal.fire({
                    title: 'Confirm Action',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    resolve(result.isConfirmed);
                });
            });
        }
    </script>
    @yield('scripts')
</body>
</html>