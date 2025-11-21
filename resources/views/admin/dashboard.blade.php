<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Google Icons for visual elements -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS merged from dashboard.css */
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
            background: #007bff; /* Blue color for Admin */
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
            border-right: 1px solid #e6e6e6;
            transition: transform 0.3s ease;
            z-index: 990;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .brand {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
        }

        .menu-label {
            margin-top: 25px;
            margin-bottom: 10px;
            color: #aaa;
            font-size: 11px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 5px;
        }

        .menu a {
            display: flex;
            align-items: center;
            padding: 12px 10px;
            text-decoration: none;
            color: #666;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }

        .menu a:hover,
        .menu li.active a {
            background: #e6f0ff; /* Light blue background for Admin active/hover */
            color: #007bff;
        }

        .menu .icon {
            margin-right: 10px;
            font-size: 20px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 230px;
            padding: 25px;
            transition: margin-left 0.3s ease;
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .logout-button {
            padding: 10px 15px;
            background: #ff4d4f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .logout-button:hover {
            background: #cc0000;
        }

        /* BREADCRUMBS */
        .breadcrumbs {
            font-size: 14px;
            color: #999;
            margin-bottom: 25px;
        }

        .breadcrumbs a {
            color: #007bff;
            text-decoration: none;
        }

        /* TABS */
        .tabs {
            display: flex;
            border-bottom: 2px solid #e6e6e6;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            transition: color 0.2s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px; /* to overlap the main border */
        }

        .tab.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }

        /* FILTERS */
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: center;
        }

        .filters input, .filters select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .action-button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* TABLE */
        .table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #fafafa;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ececec;
            text-align: left;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
        }

        .approved {
            background: #d9f3ff;
            color: #0077b6;
        }

        .pending {
            background: #fff2b2;
            color: #a88704;
        }

        /* SUMMARY CARDS */
        .summary-row {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.05);
            border-left: 5px solid #007bff;
        }
        .summary-card h4 {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-card p {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        /* MOBILE VIEW ADJUSTMENTS */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-260px);
            }

            .sidebar.visible {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>

<!-- MOBILE MENU BUTTON -->
<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <span class="material-icons">menu</span>
</button>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
  <h3 class="brand">MAYET RESORT (Admin)</h3>
  <p class="menu-label">MAIN MENU</p>

  <ul class="menu">
    <li class="active">
      <a href="{{ route('admin.dashboard') }}"><span class="icon">📊</span> Dashboard</a>
    </li>

    <li>
      <a href="#"><span class="icon">📦</span> Requests</a>
    </li>

    <li>
      <a href="#"><span class="icon">🛒</span> Inventory</a>
    </li>

    <li>
      <a href="#"><span class="icon">🧾</span> Orders</a>
    </li>

    <li>
      <a href="#"><span class="icon">👥</span> Employees</a>
    </li>
    <li>
    <a href="#">🛏 Rooms</a>
    </li>
  </ul>

  <div class="bottom-menu">
    <p class="menu-label">USER & SETTINGS</p>
    <ul class="menu">
        <li>
            <a href="#"><span class="icon">👤</span> Users</a>
        </li>
        <li>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: block;">
                @csrf
                <button type="submit" style="all: unset; cursor: pointer; display: flex; align-items: center; padding: 12px 10px; width: 100%; color: #666; border-radius: 8px; transition: background 0.2s, color 0.2s;">
                    <span class="icon" style="color: #ff4d4f;">🚪</span> Logout
                </button>
            </form>
        </li>
    </ul>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content" id="mainContent">

  <!-- HEADER -->
  <div class="header">
    <h1 style="font-size: 24px;">Dashboard</h1>
    <div class="user-info">
      <!-- Placeholder for User Image -->
      <img src="https://placehold.co/40x40/007bff/ffffff?text=AD" alt="Admin Profile">
      <div>
        <div style="font-weight: bold;">{{ Auth::user()->full_name }}</div>
        <div style="font-size: 13px; color: #888;">Administrator</div>
      </div>
    </div>
  </div>

  <!-- BREADCRUMBS -->
  <div class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Home</a> / Dashboard
  </div>

  <!-- SUMMARY CARDS -->
  <div class="summary-row">
    <div class="summary-card" style="border-left-color: #28a745;">
      <h4>Total Inventory Items</h4>
      <p>125</p>
    </div>
    <div class="summary-card" style="border-left-color: #ffc107;">
      <h4>Pending Requisitions</h4>
      <p>12</p>
    </div>
    <div class="summary-card" style="border-left-color: #17a2b8;">
      <h4>Available Rooms</h4>
      <p>30</p>
    </div>
    <div class="summary-card" style="border-left-color: #dc3545;">
      <h4>Critical Stock Level</h4>
      <p>5</p>
    </div>
  </div>


  <h2 style="font-size: 20px; font-weight: 600; margin-top: 40px; margin-bottom: 20px;">Latest Requisitions</h2>

  <!-- TABS -->
  <div class="tabs">
    <div class="tab active" data-target="all">All</div>
    <div class="tab" data-target="pending">Pending</div>
    <div class="tab" data-target="approved">Approved</div>
  </div>

  <!-- FILTERS AND ACTIONS -->
  <div class="filters">
    <input type="date" id="dateFilter" placeholder="Filter by Date">
    <input type="text" id="orderFilter" placeholder="Search by Order ID">
    <button class="action-button">Create New Requisition</button>
  </div>


  <!-- TABLE -->
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox"></th>
          <th>#</th>
          <th>Order ID</th>
          <th>Qty</th>
          <th>Status</th>
          <th>Due Date</th>
          <th>Comments</th>
        </tr>
      </thead>
      <tbody id="requisitionTableBody">
        <!-- Sample Rows (Will be dynamic in final app) -->
        <tr>
            <td><input type="checkbox"></td>
            <td>1</td>
            <td>REQ-001</td>
            <td>10</td>
            <td><span class="badge pending">Pending</span></td>
            <td>2025-11-25</td>
            <td>Urgent item needed for kitchen.</td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2</td>
            <td>REQ-002</td>
            <td>50</td>
            <td><span class="badge approved">Approved</span></td>
            <td>2025-11-22</td>
            <td>Approved for maintenance use.</td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<script>
    // Javascript for sidebar toggle (Mobile responsiveness)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('hidden');
        
        // Adjust main content margin
        if (sidebar.classList.contains('hidden')) {
            mainContent.classList.add('full-width');
        } else {
            mainContent.classList.remove('full-width');
        }
    }

    // Logic to hide sidebar on initial load if screen is small
    window.onload = function() {
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('mainContent').classList.add('full-width');
        }
    }

    // You would integrate the original dashboard.html's filtering/table logic here if you wanted to implement the dynamic table functionality.
</script>
</body>
</html>