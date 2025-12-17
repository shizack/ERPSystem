<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Portal') - Mayet Resort</title>
    
    <!-- Icons & font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Unified Employee Design System -->
    <link rel="stylesheet" href="{{ asset('css/employee-design-system.css') }}">

    @stack('styles')
</head>
<body>
    <div class="bg-deco" aria-hidden="true"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Mayet Resort Logo">
                <div>Mayet Resort</div>
            </div>
        </div>

        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(auth('employee')->user()->full_name ?? auth('employee')->user()->name ?? 'E',0,1)) }}</div>
            <div class="meta">
                <div class="name">{{ auth('employee')->user()->full_name ?? auth('employee')->user()->name ?? 'Employee' }}</div>
                <div class="role">{{ ucfirst(auth('employee')->user()->department ?? 'Staff') }}</div>
            </div>
        </div>

        <div class="sidebar-menu">
            <h4>Main</h4>
            <ul>
                <li class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('employee.dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('employee.requisitions.*') ? 'active' : '' }}">
                    <a href="{{ route('employee.requisitions.index') }}">
                        <i class="material-icons">list_alt</i>
                        <span>My Requisitions</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('employee.requisitions.create') ? 'active' : '' }}">
                    <a href="{{ route('employee.requisitions.create') }}">
                        <i class="material-icons">add_circle</i>
                        <span>New Requisition</span>
                    </a>
                </li>
            </ul>

            <div class="group-sep"></div>

            <h4>Account</h4>
                <li>
                    <a href="{{ route('employee.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons">logout</i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('employee.logout') }}" method="POST" style="display: none;">@csrf</form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="material-icons">menu</i>
    </button>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
    
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.querySelector('.mobile-menu-btn');
        if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
    </script>
</body>
</html>