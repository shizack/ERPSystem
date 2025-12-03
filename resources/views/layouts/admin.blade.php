<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Icons & font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --resort-blue: #007bff;
            --resort-yellow: #ffcc00;
            --resort-red: #ef4444;
            --surface-white: rgba(255,255,255,0.92);
            --glass-weak: rgba(255,255,255,0.55);
            --muted-ink: #223;
            --card-shadow: 0 8px 30px rgba(16, 42, 67, 0.12);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        
        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            color: var(--muted-ink);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: linear-gradient(180deg, #f6f9ff 0%, #eef7ff 35%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
        }

        .bg-deco {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(120deg, rgba(0,123,255,0.03), rgba(255,204,0,0.015));
            z-index: 0;
            backdrop-filter: blur(2px);
        }

        .sidebar {
            width: 250px;
            height: calc(100vh - 36px);
            position: fixed;
            left: 18px;
            top: 18px;
            padding: 18px;
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.52));
            backdrop-filter: blur(8px) saturate(120%);
            box-shadow: var(--card-shadow);
            z-index: 20;
            display: flex;
            flex-direction: column;
            gap: 12px;
            color: #0f1724;
            overflow: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .sidebar-header h3 {
            margin: 0;
            font-weight: 800;
            color: var(--resort-blue);
            font-size: 1.05rem;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--muted-ink);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-nav li a:hover,
        .sidebar-nav li.active a {
            background: rgba(0, 123, 255, 0.1);
            color: var(--resort-blue);
        }

        .sidebar-nav li a i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            margin-left: 286px; /* 250px + 18px + 18px */
            padding: 24px;
            position: relative;
            z-index: 10;
        }

        .card {
            background: var(--surface-white);
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 980px) {
            .sidebar {
                position: fixed;
                left: 0;
                right: 0;
                top: 0;
                bottom: 0;
                width: 280px;
                height: 100vh;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                margin: 0;
                border-radius: 0;
            }
            
            .sidebar.visible {
                transform: translateX(0);
            }
            
            .sidebar.hidden {
                transform: translateX(-100%);
            }
            
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: var(--resort-blue);
                color: white;
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .main-content {
                margin-left: 0;
                padding: 16px;
                margin-top: 12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="bg-deco" aria-hidden="true"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        @include('admin.partials.sidebar')
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
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const isClickInside = sidebar.contains(event.target) || 
                            event.target.matches('.mobile-menu-btn, .mobile-menu-btn *');
        
        if (!isClickInside && window.innerWidth <= 980) {
            sidebar.classList.add('hidden');
        }
    });

    // Handle window resize
    function handleResize() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 980) {
            sidebar.classList.remove('hidden');
        } else {
            sidebar.classList.add('hidden');
        }
    }

    // Initial setup
    window.addEventListener('DOMContentLoaded', function() {
        handleResize();
    });

    // Update on resize
    window.addEventListener('resize', handleResize);
    </script>
</body>
</html>
