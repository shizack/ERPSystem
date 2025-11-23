<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP Admin')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Custom Section for child views/styles -->
    @yield('head')
    <style>
        body { margin: 0; background: #f5f6fa; font-family: Arial,sans-serif; color: #333;}
        .sidebar { width: 230px; background: #fff; min-height: 100vh; padding: 25px 0 25px 20px; position: fixed; top:0; left:0; border-right:1px solid #e6e6e6; z-index:100; }
        .sidebar .brand { font-size: 22px; font-weight: bold; color: #007bff; margin-bottom: 35px;}
        .sidebar .menu { list-style: none; padding: 0;}
        .sidebar .menu a { display: flex; align-items:center; text-decoration:none; color:#666; border-radius:8px; padding:12px 12px 12px 8px; margin-bottom: 2px; }
        .sidebar .menu a:hover, .sidebar .menu .active > a { background: #e6f0ff; color: #007bff;}
        .sidebar .icon { margin-right: 10px; font-size: 20px;}
        .main-content { margin-left: 230px; padding: 32px 32px 32px 32px; min-height: 100vh; transition: margin-left 0.3s;}
        header { display:flex; align-items:center; justify-content:space-between; margin-bottom:25px;}
        .user-info { display: flex; align-items:center; }
        .user-info img { border-radius:50%; width:40px; height:40px; margin-right:12px; object-fit:cover; background: #ccc;}
        .logout-button { margin-left: 22px; padding: 10px 16px; background:#ff4d4f; border: none; color: #fff; border-radius:6px; cursor:pointer; transition:0.2s;}
        .logout-button:hover { background: #cc0000;}
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 24px; font-size: 15px;}
        .alert-success { background: #dafbe1; color: #317d39;}
        .alert-danger { background: #fed7d7; color: #b91c1c;}
        @media (max-width: 900px) {
            .sidebar { position: fixed; left: -240px; transition: left 0.3s;}
            .sidebar.visible { left: 0; }
            .main-content { margin-left: 0; }
            .mobile-menu-btn { display: block !important;}
        }
        .mobile-menu-btn { display: none; position: fixed; top: 20px; left: 20px; background: #007bff; color: #fff; border: none; border-radius: 6px; padding: 10px; cursor:pointer; z-index:200;}
    </style>
    @stack('styles')
</head>
<body>
<!-- MOBILE MENU BUTTON -->
<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <span class="material-icons">menu</span>
</button>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="brand">ERP Admin</div>
    <ul class="menu">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}"><span class="icon">dashboard</span> Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <a href="{{ route('admin.inventory.index') }}"><span class="icon">inventory_2</span> Inventory</a>
        </li>
        <li>
            <a href="#"><span class="icon">shopping_cart</span> Orders</a>
        </li>
        <li>
            <a href="#"><span class="icon">people</span> Employees</a>
        </li>
        <li>
            <a href="#"><span class="icon">hotel</span> Rooms</a>
        </li>
    </ul>
    <hr>
    <ul class="menu">
        <li>
            <a href="#"><span class="icon">person</span> Users</a>
        </li>
        <li>
            <form action="{{ route('admin.logout') }}" method="POST" style="display:block;">
                @csrf
                <button class="logout-button" type="submit"><span class="icon" style="color:#fff;">logout</span> Logout</button>
            </form>
        </li>
    </ul>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content" id="mainContent">
    <!-- HEADER/NAV -->
    <header>
        <h2>@yield('page-title', 'Welcome, Admin!')</h2>
        <div class="user-info">
            <img src="https://placehold.co/40x40/007bff/fff?text=AD" alt="Profile">
            <div>
                <div style="font-weight:bold;">{{ Auth::user()->full_name ?? 'Admin' }}</div>
                <div style="font-size:12px; color:#888;">Administrator</div>
            </div>
        </div>
    </header>

    <!-- Notifications/Session Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('visible');
    }
    // Close sidebar on resize for mobile
    window.onresize = function(){
        if(window.innerWidth > 900){
            document.getElementById('sidebar').classList.remove('visible');
        }
    };
</script>
@stack('scripts')
</body>
</html>