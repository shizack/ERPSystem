<!DOCTYPE html>
<html lang="en">
<head>
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

      <a href="{{ route('employee.requisitions.index') }}">
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
