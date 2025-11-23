<!DOCTYPE html>
<html lang="en">
<head>
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