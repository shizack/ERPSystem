<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Mayet Resort</title>

    <!-- Icons & font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Unified Design System -->
    <link rel="stylesheet" href="{{ asset('css/admin-design-system.css') }}">

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
            <div class="avatar">{{ strtoupper(substr(Auth::user()->full_name ?? 'A',0,1)) }}</div>
            <div class="meta">
                <div class="name">{{ Auth::user()->full_name ?? 'Administrator' }}</div>
                <div class="role">{{ Auth::user()->job_title ?? 'Administrator' }}</div>
            </div>
        </div>

        <div class="sidebar-menu">
            <h4>Main</h4>
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.inventory.index') }}">
                        <i class="material-icons">inventory</i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.purchase-orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.purchase-orders.index') }}">
                        <i class="material-icons">shopping_bag</i>
                        <span>Purchase Orders</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.requisitions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.requisitions.all') }}">
                        <i class="material-icons">list_alt</i>
                        <span>Requisitions</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}">
                        <i class="material-icons">local_shipping</i>
                        <span>Suppliers</span>
                    </a>
                </li>
            </ul>

            <div class="group-sep"></div>

            <h4>System</h4>
            <ul>
                <li>
                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons">logout</i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
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
