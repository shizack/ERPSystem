<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Portal - ERP System')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .main-content {
            margin-left: 16rem; /* Same as sidebar width */
            min-height: 100vh;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .menu-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            display: none;
        }
        .page-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
            color: #4b5563;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .logout-btn:hover {
            background-color: #f3f4f6;
        }
        @media (max-width: 768px) {
            .menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div id="app">
        <!-- Top Navigation -->
        <nav class="navbar">
            <div class="navbar-left">
                <button id="sidebarToggle" class="menu-btn">
                    <i class="material-icons">menu</i>
                </button>
                <h2 class="page-title">@yield('title', 'Dashboard')</h2>
            </div>
            <div class="navbar-right">
                <div class="user-info">
                    <span>Welcome, {{ auth('employee')->user()->name }}</span>
                    <a href="{{ route('employee.logout') }}" class="logout-btn">
                        <i class="material-icons">logout</i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            @include('admin.partials.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <!-- Page Content -->
                <main class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div> <!-- Close main-content -->
        </div> <!-- Close flex -->
    </div> <!-- Close app -->

    <!-- Scripts -->
    @stack('scripts')
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        });
    </script>
</body>
</html>