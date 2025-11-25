<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Employee Dashboard</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    /* ---------- Palette ---------- */
    :root{
      --resort-blue: #007bff;
      --resort-yellow: #ffcc00;
      --resort-red: #ef4444;
      --surface-white: rgba(255,255,255,0.92);
      --glass-weak: rgba(255,255,255,0.55);
      --muted-ink: #223;
      --card-shadow: 0 8px 30px rgba(16, 42, 67, 0.12);
    }

    /* ---------- Base ---------- */
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color:var(--muted-ink);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;

      /* plain resort-inspired gradient background (option B) */
      background: linear-gradient(180deg, #f6f9ff 0%, #eef7ff 35%, #ffffff 100%);
      min-height:100vh;
      display:flex;
    }

    /* subtle full-page glass overlay like login */
    .bg-deco {
      position:fixed;
      inset:0;
      pointer-events:none;
      background: linear-gradient(120deg, rgba(0,123,255,0.03), rgba(255,204,0,0.015));
      z-index:0;
      backdrop-filter: blur(2px);
    }

    /* ---------- Layout ---------- */
    .sidebar {
      width:250px;
      height:100vh;
      position:fixed;
      left:18px;
      top:18px;
      padding:22px;
      border-radius:14px;
      background: linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.52));
      backdrop-filter: blur(8px) saturate(120%);
      box-shadow: var(--card-shadow);
      z-index:20;
      display:flex;
      flex-direction:column;
      gap:12px;
    }

    /* logo / title */
    .sidebar .brand {
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:800;
      color:var(--resort-blue);
      font-size:1.05rem;
    }
    .brand .logo-img{ width:46px; height:46px; border-radius:10px; background: linear-gradient(90deg,var(--resort-blue),var(--resort-yellow)); box-shadow:0 4px 12px rgba(0,0,0,0.08) }

    /* user block */
    .user-info{
      margin-top:6px;
      padding:12px;
      border-radius:12px;
      background: rgba(255,255,255,0.35);
      display:flex;
      gap:12px;
      align-items:center;
      border:1px solid rgba(255,255,255,0.35);
    }
    .user-info .avatar {
      width:48px;height:48px;border-radius:9999px;
      display:grid;place-items:center;
      font-size:22px;color:white;
      background: linear-gradient(120deg,var(--resort-blue),var(--resort-yellow));
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }
    .user-info .meta{ font-size:0.9rem }
    .user-info .meta .name{ font-weight:700; color: #08203a }
    .user-info .meta .role{ font-size:0.8rem; color:#6b7280 }

    /* nav */
    .nav {
      margin-top:8px;
      display:flex;
      flex-direction:column;
      gap:8px;
    }
    .nav a{
      display:flex; align-items:center; gap:12px;
      padding:10px 12px;border-radius:10px;text-decoration:none;
      color: #0f1724; font-weight:600;
      transition: all .18s ease;
      background: transparent;
    }
    .nav a .material-icons{ color: var(--resort-blue); font-size:20px }
    .nav a:hover{
      transform:translateY(-2px);
      box-shadow: 0 8px 24px rgba(2,6,23,0.04);
      background: linear-gradient(90deg, rgba(0,123,255,0.06), rgba(255,204,0,0.04));
    }

    .nav .group-sep{ height:1px; background: linear-gradient(90deg, rgba(2,6,23,0.04), rgba(2,6,23,0.02)); margin:10px 0; border-radius:2px }

    /* logout button */
    .logout {
      margin-top:auto;
      display:block;
      width:100%;
      padding:10px;
      border-radius:10px;
      text-align:left;
      font-weight:700;
      color:var(--resort-red);
      background: linear-gradient(180deg, rgba(255,255,255,0.4), rgba(255,255,255,0.25));
      border:1px solid rgba(239,68,68,0.08);
      cursor:pointer;
    }

    /* ---------- Main content area ---------- */
    .content-wrap{
      margin-left:290px; /* leave a gap so sidebar floats like a card */
      padding:28px;
      width:100%;
      min-height:100vh;
      z-index:10;
    }

    .page-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:16px;
      margin-bottom:18px;
    }
    .page-title{
      font-size:1.6rem;
      font-weight:800;
      color: #0b2540;
    }

    /* header controls */
    .header-controls{
      display:flex;
      gap:12px;
      align-items:center;
    }
    .search {
      display:flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:10px;
      background: rgba(255,255,255,0.7);
      box-shadow: 0 4px 18px rgba(2,6,23,0.04);
      border:1px solid rgba(2,6,23,0.03);
    }
    .search input{
      border:none; outline:none; background:transparent; width:180px; font-weight:600;
    }

    .profile-mini {
      display:flex; align-items:center; gap:10px;
      padding:8px 10px; border-radius:10px;
      background: linear-gradient(90deg, rgba(0,123,255,0.06), rgba(255,204,0,0.02));
      border:1px solid rgba(2,6,23,0.03);
    }
    .profile-mini .material-icons{ color: var(--resort-blue) }

    /* ---------- Cards ---------- */
    .card-grid{
      display:grid;
      grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
      gap:18px;
      margin-bottom:22px;
    }

    .card{
      padding:18px;
      border-radius:12px;
      background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.70));
      backdrop-filter: blur(6px) saturate(120%);
      box-shadow: var(--card-shadow);
      border-left: 6px solid var(--resort-blue);
      min-height:110px;
      display:flex; flex-direction:column; justify-content:space-between;
    }
    .card .label{ font-size:0.85rem; color:#6b7280; font-weight:700; }
    .card .value{ font-size:1.9rem; font-weight:900; color:#081126; }

    /* small colored accent dots */
    .card .accent{
      width:38px;height:38px;border-radius:8px; display:grid; place-items:center;
      font-weight:800;color:white;
      background: linear-gradient(90deg,var(--resort-blue),var(--resort-yellow));
      box-shadow:0 6px 18px rgba(2,6,23,0.06);
    }

    /* ---------- Table ---------- */
    .table-panel{
      border-radius:12px;
      padding:16px;
      background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(255,255,255,0.78));
      box-shadow: var(--card-shadow);
      overflow:auto;
    }
    .table-panel h2{ margin:0 0 12px 0; color:#0b2540 }
    table{
      width:100%;
      border-collapse:separate;
      border-spacing:0 10px;
    }
    thead th{
      text-align:left; padding:12px 14px;
      color:#475569; font-weight:800; font-size:12px; text-transform:uppercase;
    }
    tbody tr{
      background: rgba(255,255,255,0.95);
      border-radius:10px;
      box-shadow: 0 4px 0 rgba(2,6,23,0.02);
    }
    td{ padding:12px 14px; vertical-align:middle; color:#08203a; font-weight:600 }

    /* badges: using resort colors where appropriate */
    .badge{ display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:800; text-transform:uppercase }
    .badge.pending{ background: rgba(255,204,0,0.12); color: #b45309; border:1px solid rgba(255,204,0,0.08) }
    .badge.approved{ background: rgba(0,123,255,0.10); color: var(--resort-blue); border:1px solid rgba(0,123,255,0.06) }
    .badge.rejected{ background: rgba(239,68,68,0.10); color: var(--resort-red); border:1px solid rgba(239,68,68,0.06) }

    /* tiny utility */
    .muted{ color:#6b7280; font-weight:600; font-size:0.9rem }

    /* responsive behavior */
    @media (max-width: 980px){
      .sidebar{ left:12px; right:12px; width:calc(100% - 24px); top:12px; height:auto; position:relative; border-radius:12px; }
      .content-wrap{ margin-left:0; padding:16px; margin-top:12px }
      .search input{ width:110px }
    }
    @media (max-width: 640px){
      .brand{ font-size:1rem } 
      .card .value{ font-size:1.45rem }
      .search input{ width:80px }
    }
  </style>
</head>
<body>

  <div class="bg-deco" aria-hidden="true"></div>

  <!-- SIDEBAR (glass card style) -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Mayet Resort Logo" style="width:46px; height:46px; border-radius:10px; object-fit:cover;">
        <div>Mayet Resort</div>
    </div>


    <div class="user-info">
      <div class="avatar">
        <!-- First letter of user name -->
        @if (Auth::guard('employee')->check())
          {{ strtoupper(substr(Auth::guard('employee')->user()->name,0,1)) }}
        @else
          G
        @endif
      </div>
      <div class="meta">
        <div class="name">
          @if (Auth::guard('employee')->check())
            {{ Auth::guard('employee')->user()->name }}
          @else
            Guest Employee
          @endif
        </div>
        <div class="role">Employee</div>
      </div>
    </div>

    <nav class="nav">
      <a href="{{ route('employee.dashboard') }}">
        <span class="material-icons">dashboard</span> Dashboard
      </a>

      <a href="{{ route('employee.requisitions.create') }}">
        <span class="material-icons">add_shopping_cart</span> New Requisition
      </a>

      <a href="#">
        <span class="material-icons">list_alt</span> View Requisitions
      </a>

      <div class="group-sep" aria-hidden="true"></div>

      <a href="#">
        <span class="material-icons">info</span> Help & Support
      </a>
    </nav>

    <form method="POST" action="{{ route('employee.logout') }}">
      @csrf
      <button class="logout" type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
        <span class="material-icons" style="vertical-align:middle">logout</span> Logout
      </button>
    </form>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content-wrap" id="mainContent">
    <header class="page-header">
      <div>
        <div class="page-title">Welcome back, {{ Auth::guard('employee')->check() ? strtok(Auth::guard('employee')->user()->name,' ') : 'Employee' }}!</div>
        <div class="muted" style="margin-top:6px">Overview of recent activity</div>
      </div>

      <div class="header-controls">
        <div class="search" role="search">
          <span class="material-icons" aria-hidden="true">search</span>
          <input placeholder="Search requisitions..." />
        </div>

        <div class="profile-mini">
          <span class="material-icons">notifications</span>
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="font-weight:800; color:var(--resort-blue)">{{ Auth::guard('employee')->check() ? strtok(Auth::guard('employee')->user()->name,' ') : 'Guest' }}</div>
          </div>
        </div>
      </div>
    </header>

    @if (session('status'))
      <div style="margin-bottom:18px; padding:12px; border-radius:10px; background: linear-gradient(90deg, rgba(0,123,255,0.06), rgba(255,204,0,0.03)); border:1px solid rgba(0,123,255,0.05);">
        <span class="material-icons" style="vertical-align:middle; margin-right:8px; color:var(--resort-blue)">check_circle</span>
        <strong>{{ session('status') }}</strong>
      </div>
    @endif

    <!-- CARDS -->
    <section class="card-grid" aria-label="Key metrics">
      <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
          <div>
            <div class="label">Pending Requisitions</div>
            <div class="value">3</div>
          </div>
          <div class="accent">P</div>
        </div>
        <div class="muted" style="margin-top:6px">Requires attention</div>
      </div>

      <div class="card" style="border-left-color:var(--resort-yellow);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
          <div>
            <div class="label">Approved This Month</div>
            <div class="value">15</div>
          </div>
          <div class="accent" style="background: linear-gradient(90deg,var(--resort-yellow),var(--resort-blue))">A</div>
        </div>
        <div class="muted" style="margin-top:6px">Positive progress</div>
      </div>

      <div class="card" style="border-left-color:var(--resort-red);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
          <div>
            <div class="label">Monthly Budget Used</div>
            <div class="value">$8,500</div>
          </div>
          <div class="accent" style="background: linear-gradient(90deg,var(--resort-red),#ff7a7a)">$</div>
        </div>
        <div class="muted" style="margin-top:6px">Review if approaching limit</div>
      </div>
    </section>

    <!-- Table -->
    <section class="table-panel" aria-label="Recent requisitions">
      <h2>Recent Requisitions</h2>

      <table role="table" aria-label="Recent Requisitions Table">
        <thead>
          <tr>
            <th></th>
            <th>ID</th>
            <th>Ref No.</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="checkbox" aria-label="select row"></td>
            <td>1</td>
            <td>REQ-001</td>
            <td>10</td>
            <td><span class="badge pending">Pending</span></td>
            <td>2025-11-25</td>
            <td>Urgent item needed.</td>
          </tr>

          <tr>
            <td><input type="checkbox" aria-label="select row"></td>
            <td>2</td>
            <td>REQ-002</td>
            <td>5</td>
            <td><span class="badge approved">Approved</span></td>
            <td>2025-11-22</td>
            <td>Approved for cleaning supplies.</td>
          </tr>

          <tr>
            <td><input type="checkbox" aria-label="select row"></td>
            <td>3</td>
            <td>REQ-003</td>
            <td>2</td>
            <td><span class="badge rejected">Rejected</span></td>
            <td>2025-11-21</td>
            <td>Request for a new high-end monitor.</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>

  <script>
    // No heavy JS needed — layout is responsive via CSS.
    // If you want a collapsible / floating sidebar on small screens,
    // I can wire that up next. For now the sidebar becomes full-width on small viewports.
  </script>
</body>
</html>
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <!-- Google Icons for visual elements -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS merged from dashboard.css, adjusted for Employee branding (Indigo/Purple) */
        /* GENERAL */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        /* MOBILE MENU BUTTON (Hidden by default, shown on mobile) */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background: #4f46e5; /* Indigo color for Employee */
            color: white;
            padding: 10px;
            border-radius: 6px;
            z-index: 1000;
            cursor: pointer;
            border: none;
        }

        .mobile-menu-btn .material-icons {
            font-size: 26px;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            background: white;
            height: 100vh;
            padding: 25px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            z-index: 999;
        }
        
        /* Sidebar Scrollbar Styling (Optional, for better look) */
        .sidebar-content {
            overflow-y: auto;
            max-height: calc(100% - 100px); /* Adjust based on logo/header height */
            padding-right: 15px; /* Space for scrollbar */
        }
        .sidebar-content::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar-content::-webkit-scrollbar-thumb {
            background-color: #e0e0e0;
            border-radius: 4px;
        }
        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4f46e5; /* Employee Brand Color */
            margin-bottom: 30px;
            text-align: center;
        }

        .user-info {
            padding: 10px 0;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .user-info .material-icons {
            font-size: 40px;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: #333;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar a:hover {
            background: #e0e7ff; /* Light Indigo background */
            color: #3730a3; /* Darker Indigo text */
        }

        .sidebar .material-icons {
            margin-right: 15px;
            font-size: 20px;
            color: #4f46e5; /* Icon color */
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 230px; /* Initial margin for desktop */
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content.full-width {
            margin-left: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            margin: 0;
            color: #4f46e5;
        }
        
        /* Status Message Styling */
        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .status-message.success {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-700 */
            border: 1px solid #a7f3d0; /* green-200 */
        }
        .status-message .material-icons {
            margin-right: 10px;
            font-size: 20px;
        }


        /* DASHBOARD CARDS */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
            border-left: 5px solid #4f46e5; /* Primary accent */
        }

        .card-header {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #111827;
        }

        /* TABLE STYLING */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; /* Space between rows */
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.pending {
            background-color: #fef3c7; /* yellow-100 */
            color: #b45309; /* amber-700 */
        }

        .badge.approved {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-700 */
        }

        .badge.rejected {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-700 */
        }

        /* MEDIA QUERIES for Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
                /* When opened, it covers the whole screen */
                width: 70%;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }
            
            /* Add an overlay when sidebar is open */
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 990;
                display: none;
            }
            
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Mobile Menu Button -->
<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <span class="material-icons">menu</span>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="logo">
        ERP Employee Portal
    </div>

    <div class="user-info">
        <span class="material-icons">account_circle</span>
        <!-- Display Employee Name -->
        <p class="font-bold text-lg">
            @if (Auth::guard('employee')->check())
                {{ Auth::guard('employee')->user()->name }}
            @else
                Guest Employee
            @endif
        </p>
        <p class="text-sm text-gray-500">Employee Role</p>
    </div>

    <div class="sidebar-content">
        <!-- Dashboard Link -->
        <a href="{{ route('employee.dashboard') }}">
            <span class="material-icons">dashboard</span>
            Dashboard
        </a>

        <!-- New Requisition Link (Updated to use the new route) -->
        <a href="{{ route('employee.requisitions.create') }}">
            <span class="material-icons">add_shopping_cart</span>
            New Requisition
        </a>

        <!-- View Requisitions Link (Placeholder) -->
        <a href="#">
            <span class="material-icons">list_alt</span>
            View Requisitions
        </a>

        <div style="border-top: 1px solid #eee; margin: 20px 0;"></div>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('employee.logout') }}">
            @csrf
            <button type="submit" style="all: unset; cursor: pointer; width: 100%; text-align: left;">
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #ef4444;">
                    <span class="material-icons">logout</span>
                    Logout
                </a>
            </button>
        </form>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content" id="mainContent">

    <div class="header">
        <h1>Welcome Back, {{ Auth::guard('employee')->check() ? strtok(Auth::guard('employee')->user()->name, ' ') : 'Employee' }}!</h1>
        <!-- Search bar or other header elements can go here -->
    </div>

    <!-- Session Status Message Display -->
    @if (session('status'))
        <div class="status-message success">
            <span class="material-icons">check_circle</span>
            {{ session('status') }}
        </div>
    @endif
    
    <!-- DASHBOARD CARDS -->
    <div class="card-container">
        <!-- Card 1: Pending Requisitions -->
        <div class="card">
            <div class="card-header">Pending Requisitions</div>
            <div class="card-value">3</div>
        </div>

        <!-- Card 2: Approved Requisitions -->
        <div class="card">
            <div class="card-header">Approved This Month</div>
            <div class="card-value">15</div>
        </div>

        <!-- Card 3: Total Spend (Placeholder) -->
        <div class="card">
            <div class="card-header">Monthly Budget Used</div>
            <div class="card-value">$8,500</div>
        </div>
    </div>

    <!-- RECENT REQUISITIONS TABLE -->
    <div class="table-container">
        <h2>Recent Requisitions</h2>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Ref No.</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder data (should be dynamic in final app) -->
                <tr>
                    <td><input type="checkbox"></td>
                    <td>1</td>
                    <td>REQ-001</td>
                    <td>10</td>
                    <td><span class="badge pending">Pending</span></td>
                    <td>2025-11-25</td>
                    <td>Urgent item needed.</td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>2</td>
                    <td>REQ-002</td>
                    <td>5</td>
                    <td><span class="badge approved">Approved</span></td>
                    <td>2025-11-22</td>
                    <td>Approved for cleaning supplies.</td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>3</td>
                    <td>REQ-003</td>
                    <td>2</td>
                    <td><span class="badge rejected">Rejected</span></td>
                    <td>2025-11-21</td>
                    <td>Request for a new high-end monitor.</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    // Javascript for sidebar toggle (Mobile responsiveness)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Toggle the 'active' class for mobile view
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        } else {
            // For desktop, just hide/show the sidebar
            sidebar.classList.toggle('hidden');
            
            // Adjust main content margin for desktop view only
            const mainContent = document.getElementById('mainContent');
            mainContent.style.marginLeft = sidebar.classList.contains('hidden') ? '0' : '230px';
        }
    }

    // Logic for initial load and resize
    window.onload = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (window.innerWidth <= 768) {
            // Hide sidebar and remove margin on mobile
            sidebar.classList.remove('active'); // ensure it's hidden initially on mobile
            sidebar.classList.add('hidden');
            mainContent.style.marginLeft = '0';
        } else {
            // Ensure sidebar is visible and margin is correct on desktop
            sidebar.classList.remove('hidden');
            mainContent.style.marginLeft = '230px';
        }
    }
    
    window.onresize = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (window.innerWidth > 768) {
            // Ensure sidebar is visible on desktop resize and main content margin is correct
            sidebar.classList.remove('hidden');
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            mainContent.style.marginLeft = '230px';
        } else {
             // Ensure sidebar is hidden on mobile resize
             // The hidden class ensures the translateX(-100%) style is applied
            sidebar.classList.add('hidden'); 
            sidebar.classList.remove('active');
            mainContent.style.marginLeft = '0';
        }
    }
</script>
</body>
</html>
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
