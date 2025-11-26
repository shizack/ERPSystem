<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERP Admin')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('head')

<style>
    body { 
        margin: 0; 
        background: #eef3fb; 
        font-family: "Inter", Arial, sans-serif; 
        color: #333;
    }

    /* -------------------- SIDEBAR -------------------- */
    .sidebar { 
        width: 260px; 
        background: #ffffff;
        min-height: 100vh; 
        padding: 35px 0; 
        position: fixed; 
        top:0; 
        left:0; 
        border-right: 1px solid #e3e8ef; 
        z-index:100;

        /* Modern rounded style */
        box-shadow: 4px 0 18px rgba(0,0,0,0.06);
        border-radius: 0 22px 22px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Logo */
    .sidebar .logo-wrapper {
        text-align: center;
        margin-bottom: 15px;
    }

    .sidebar .logo-wrapper img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Brand name */
    .sidebar .brand { 
        font-size: 23px; 
        font-weight: 700; 
        color: #007bff; 
        margin: 12px 0 35px;
        text-align: center;
        letter-spacing: 0.5px;
    }

    /* Menu */
    .sidebar .menu { 
        list-style: none; 
        padding: 0; 
        width: 88%; 
    }

    .sidebar .menu a {
        display: flex; 
        align-items:center; 
        text-decoration:none; 
        color:#4a5568; 
        border-radius:14px; 
        padding:12px 15px;
        margin-bottom: 8px;

        font-weight: 500;
        transition: 0.25s;
        font-size: 15px;
    }

    .sidebar .menu a:hover,
    .sidebar .menu .active > a { 
        background: #e6f0ff; 
        color: #007bff;
        transform: translateX(5px);
    }

    .sidebar .icon { 
        margin-right: 14px; 
        font-size: 22px;
    }

    hr {
        width: 80%;
        border: none;
        height: 1px;
        background: #e3e8ef;
        margin: 25px 0;
    }

    /* Logout button */
    .logout-button { 
        margin-top: 10px;
        padding: 12px 16px; 
        background:#ff4d4f; 
        border: none; 
        color: #fff; 
        border-radius:12px;
        cursor:pointer; 
        transition:0.2s;
        font-weight: 600;
        width: 100%;
        text-align: left;
    }

    .logout-button:hover { background: #d22728; }

    /* -------------------- MAIN CONTENT -------------------- */
    .main-content { 
        margin-left: 260px; 
        padding: 35px; 
        min-height: 100vh;
    }

    /* Header card */
    header { 
        display:flex; 
        align-items:center; 
        justify-content:space-between; 
        margin-bottom:28px;

        background: #ffffff;
        padding: 20px 25px;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }

    header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    /* User info */
    .user-info { display: flex; align-items:center; }
    .user-info img { 
        border-radius:50%; 
        width:50px; 
        height:50px; 
        margin-right:12px; 
        object-fit:cover; 
        background: #d6d6d6;
        box-shadow: 0 2px 9px rgba(0,0,0,0.15);
    }

    /* Alerts */
    .alert { 
        padding: 15px; 
        border-radius: 14px; 
        margin-bottom: 22px; 
        font-size: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .alert-success { background: #ddf7e6; color: #2c7a3f; }
    .alert-danger { background: #ffe2e2; color: #b91c1c; }

    /* -------------------- MOBILE -------------------- */
    @media (max-width: 900px) {
        .sidebar { 
            left: -260px; 
            transition: left 0.3s;
        }
        .sidebar.visible { left: 0; }
        .main-content { margin-left: 0; }
        .mobile-menu-btn { display: block !important;}
    }

    .mobile-menu-btn { 
        display: none; 
        position: fixed; 
        top: 20px; 
        left: 20px; 
        background: #007bff; 
        color: #fff; 
        border: none; 
        border-radius: 10px; 
        padding: 10px; 
        cursor:pointer; 
        z-index:200;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }
</style>

    @stack('styles')
</head>

<body>

<!-- MOBILE MENU BUTTON -->
<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <span class="material-icons">menu</span>
</button>

<!-- -------------------- SIDEBAR -------------------- -->
<aside class="sidebar" id="sidebar">

    <div class="logo-wrapper">
        <img src="/images/logo.png" alt="Resort Logo">
    </div>

    <div class="brand">Mayet Resort</div>

    <ul class="menu">
        <!-- Dashboard -->
        <li class="{{ request()->routeIs('admin.dashboard', 'employee.dashboard') ? 'active' : '' }}">
            @if(auth('admin')->check())
                <a href="{{ route('admin.dashboard') }}">
            @elseif(auth('employee')->check())
                <a href="{{ route('employee.dashboard') }}">
            @else
                <a href="#">
            @endif
                <span class="material-icons icon">dashboard</span> 
                Dashboard
            </a>
        </li>

        @if(auth('admin')->check())
            <!-- Inventory - Only for admin -->
            <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <a href="{{ route('admin.inventory.index') }}">
                    <span class="material-icons icon">inventory_2</span>
                    Inventory
                </a>
            </li>
        @endif

        <!-- Requisitions -->
        <li class="{{ request()->routeIs('employee.requisitions.*') || request()->routeIs('admin.requisitions.*') ? 'active' : '' }}">
            @if(auth('admin')->check())
                <a href="{{ route('admin.requisitions.index') }}">
                    <span class="material-icons icon">receipt</span>
                    Requisitions
                </a>
            @elseif(auth('employee')->check())
                <a href="{{ route('employee.requisitions.index') }}">
                    <span class="material-icons icon">receipt</span>
                    My Requisitions
                </a>
            @endif
        </li>

        <!-- Employees -->
        <li>
            <a href="#">
                <span class="material-icons icon">people</span>
                Employees
            </a>
        </li>

        <!-- Rooms -->
        <li>
            <a href="#">
                <span class="material-icons icon">hotel</span>
                Rooms
            </a>
        </li>
    </ul>

    <hr>

    <ul class="menu">
        @if(auth('admin')->check())
            <!-- Users -->
            <li>
                <a href="#">
                    <span class="material-icons icon">person</span>
                    Users
                </a>
            </li>
        @endif

        <!-- Logout -->
        <li>
            @if(auth('admin')->check())
                <form action="{{ route('admin.logout') }}" method="POST" style="width:100%;">
            @elseif(auth('employee')->check())
                <form action="{{ route('employee.logout') }}" method="POST" style="width:100%;">
            @endif
                @csrf
                <button class="logout-button" type="submit">
                    <span class="material-icons icon" style="color:#fff;">logout</span>
                    Logout
                </button>
            </form>
        </li>

    </ul>

</aside>



<!-- -------------------- MAIN CONTENT -------------------- -->
<main class="main-content" id="mainContent">

    <header>
        @php
            $welcomeName = 'User';
            if (auth('admin')->check()) {
                $welcomeName = auth('admin')->user()->first_name ?? 'Admin';
            } elseif (auth('employee')->check()) {
                $welcomeName = auth('employee')->user()->first_name ?? 'Employee';
            } elseif (Auth::check()) {
                $welcomeName = Auth::user()->first_name ?? 'User';
            }
        @endphp
        <h2>@yield('page-title', 'Welcome, ' . $welcomeName . '!')</h2>

        <div class="user-info">
            <img src="https://placehold.co/50x50/007bff/fff?text={{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? 'S', 0, 1)) }}" alt="Profile">
            <div>
                <div style="font-weight:bold;">{{ Auth::user()->full_name ?? 'User' }}</div>
                <div style="font-size:12px; color:#888; text-transform:capitalize;">
                    @auth('admin')
                        Administrator
                    @elseauth('employee')
                        {{ Auth::guard('employee')->user()->role ?? 'Employee' }}
                    @else
                        User
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')

</main>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('visible');
}

window.onresize = function(){
    if(window.innerWidth > 900){
        document.getElementById('sidebar').classList.remove('visible');
    }
};
</script>

@stack('scripts')
</body>
</html>
