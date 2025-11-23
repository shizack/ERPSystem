<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inventory Management</title>
    <!-- Google Icons for visual elements -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* General Setup */
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f6fa; color: #333; }
        .sidebar { width: 230px; background: #1e3a8a; height: 100vh; padding: 25px; position: fixed; top: 0; left: 0; transition: transform 0.3s ease; }
        .sidebar-hidden { transform: translateX(-280px); }
        .main-content { margin-left: 230px; padding: 20px; transition: margin-left 0.3s ease; }
        .mobile-menu-btn { display: none; position: fixed; top: 15px; left: 15px; background: #1e3a8a; color: white; padding: 10px; border-radius: 6px; z-index: 1000; cursor: pointer; border: none; }
        .mobile-menu-btn .material-icons { font-size: 26px; }

        /* Sidebar Styling */
        .sidebar-header { color: white; margin-bottom: 40px; font-size: 1.5rem; font-weight: bold; }
        .nav-link { display: flex; align-items: center; padding: 10px 15px; margin: 8px 0; color: #bfdbfe; text-decoration: none; border-radius: 6px; transition: background 0.2s, color 0.2s; }
        .nav-link:hover, .nav-link.active { background: #3b82f6; color: white; }
        .nav-link .material-icons { margin-right: 10px; }

        /* Table Styling */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden; }
        .data-table thead tr { background: #eff6ff; color: #1e3a8a; text-align: left; }
        .data-table th, .data-table td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .data-table tbody tr:hover { background: #f9fafb; }

        /* Badge Styles */
        .badge { padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; display: inline-block; }
        .low-stock { background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; }
        .in-stock { background-color: #ecfdf5; color: #10b981; border: 1px solid #6ee7b7; }
        .out-of-stock { background-color: #fefce8; color: #f59e0b; border: 1px solid #fcd34d; }

        /* Action Buttons */
        .action-btn { background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 0.875rem; transition: background 0.2s; }
        .action-btn:hover { background: #2563eb; }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-280px); z-index: 999; }
            .sidebar-visible { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-menu-btn { display: block; }
        }
    </style>
</head>
<body>

<!-- Mobile Menu Button -->
<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <i class="material-icons">menu</i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">Admin Panel</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <i class="material-icons">dashboard</i> Dashboard
    </a>
    <a href="{{ route('admin.inventory.index') }}" class="nav-link active">
        <i class="material-icons">inventory_2</i> Inventory
    </a>
    <a href="#" class="nav-link">
        <i class="material-icons">people</i> Employees
    </a>
    <a href="#" class="nav-link">
        <i class="material-icons">receipt</i> Requisitions
    </a>
    <!-- Logout Form -->
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: 40px;">
        @csrf
        <button type="submit" class="nav-link" style="color: #f87171; background: none; border: none; width: 100%; justify-content: start;">
            <i class="material-icons" style="color: #f87171;">logout</i> Logout
        </button>
    </form>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <h1 style="font-size: 2rem; color: #1e3a8a; margin-bottom: 20px;">Inventory Overview</h1>

    @if (session('status'))
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 6px; background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('admin.inventory.create') }}" class="action-btn" style="background: #10b981; padding: 10px 15px;">
            <i class="material-icons" style="font-size: 18px; margin-right: 5px; vertical-align: middle;">add_circle</i>
            Add New Item
        </a>
        <input type="text" id="searchInput" placeholder="Search by Name or SKU..." style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; width: 300px;">
    </div>

    <!-- Inventory Table -->
    <div style="overflow-x: auto;">
        <table class="data-table" id="inventoryTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Min Stock</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                <tr data-name="{{ strtolower($item['name']) }}" data-sku="{{ strtolower($item['sku']) }}">
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['sku'] }}</td>
                    <td>{{ $item['category'] }}</td>
                    <td>{{ $item['current_stock'] }}</td>
                    <td>{{ $item['min_stock'] }}</td>
                    <td>${{ number_format($item['unit_price'], 2) }}</td>
                    <td>
                        <span class="badge {{ str_replace(' ', '-', strtolower($item['status'])) }}">
                            {{ $item['status'] }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.inventory.show', $item['id']) }}" title="View Details" class="text-blue-600 hover:text-blue-800" style="margin-right: 10px;">
                            <i class="material-icons" style="font-size: 18px; vertical-align: middle;">visibility</i>
                        </a>
                        {{-- The 'delete' route uses a form for security and proper RESTful action --}}
                        <form action="{{ route('admin.inventory.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete Item" class="text-red-600 hover:text-red-800" style="background: none; border: none; cursor: pointer;">
                                <i class="material-icons" style="font-size: 18px; vertical-align: middle;">delete</i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">No inventory items found. Add a new item to get started.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Javascript for sidebar toggle (Mobile responsiveness)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('sidebar-hidden');

        if (window.innerWidth <= 768) {
             // Only show/hide the sidebar with transition on mobile
             sidebar.classList.toggle('sidebar-visible');
        } else {
            // Adjust margin for desktop view
            mainContent.style.marginLeft = sidebar.classList.contains('sidebar-hidden') ? '0' : '230px';
        }
    }

    // Logic to hide sidebar on initial load if screen is small
    window.onload = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        if (window.innerWidth <= 768) {
            sidebar.classList.add('sidebar-hidden');
            mainContent.style.marginLeft = '0';
        } else {
            sidebar.classList.remove('sidebar-hidden');
            mainContent.style.marginLeft = '230px';
        }

        // Set up search listener
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
    }

    window.onresize = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        if (window.innerWidth > 768) {
            // Ensure sidebar is visible and margin is correct on desktop resize
            sidebar.classList.remove('sidebar-hidden', 'sidebar-visible');
            mainContent.style.marginLeft = '230px';
        } else {
             // Ensure sidebar is hidden on mobile resize
            sidebar.classList.add('sidebar-hidden');
            mainContent.style.marginLeft = '0';
            sidebar.classList.remove('sidebar-visible');
        }
    }

    // Javascript for client-side table filtering
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('inventoryTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip header row
            const row = tr[i];
            const name = row.getAttribute('data-name');
            const sku = row.getAttribute('data-sku');

            if (name.includes(input) || sku.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
</script>
</body>
</html>