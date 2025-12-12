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
            <div class="avatar">{{ strtoupper(substr(optional(Auth::guard('admin')->user())->full_name ?? 'A',0,1)) }}</div>
            <div class="meta">
                <div class="name">{{ optional(Auth::guard('admin')->user())->full_name ?? 'Administrator' }}</div>
                <div class="role">{{ optional(Auth::guard('admin')->user())->job_title ?? 'Administrator' }}</div>
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
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    @if(optional(Auth::guard('admin')->user())->job_title === 'Administrator')
                        <a href="{{ route('admin.users.create') }}">
                            <i class="material-icons">person_add</i>
                            <span>Create User</span>
                        </a>
                    @else
                        <a href="#" onclick="event.preventDefault(); showContactSuperAdminModal(); return false;">
                            <i class="material-icons">person_add</i>
                            <span>Create User</span>
                        </a>
                    @endif
                </li>
            </ul>

            <div class="group-sep"></div>

            <h4>System</h4>
            <ul>
                <li>
                    <a href="#"><i class="material-icons">settings</i><span>Settings</span></a>
                </li>
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
    // Simple modal for non-super admin action warning
    function showContactSuperAdminModal(){
        const modal = document.getElementById('contactSuperAdminModal');
        if(modal){ modal.style.display = 'flex'; }
    }
    function closeContactSuperAdminModal(){
        const modal = document.getElementById('contactSuperAdminModal');
        if(modal){ modal.style.display = 'none'; }
    }
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

    <!-- Contact Super Admin Modal -->
    <div id="contactSuperAdminModal" style="display:none; position:fixed; inset:0; background:rgba(2,6,23,0.35); backdrop-filter: blur(2px); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; box-shadow:0 10px 30px rgba(2,6,23,0.18); width:95%; max-width:440px;">
            <div style="padding:16px 18px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                <h3 style="margin:0; font-size:1rem; font-weight:800; color:#0b2540; display:flex; align-items:center; gap:8px;">
                    <i class="material-icons" style="color:#ef4444;">error_outline</i>
                    Action Not Allowed
                </h3>
                <button onclick="closeContactSuperAdminModal()" style="border:0; background:transparent; color:#64748b; cursor:pointer;">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div style="padding:18px; color:#334155;">
                <p style="margin:0 0 12px 0;">Only the Super Admin can create or manage users.</p>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:12px; border-radius:10px; display:flex; align-items:center; gap:8px;">
                    <i class="material-icons" style="color:#0ea5e9;">info</i>
                    <span>Please contact the Super Admin to create users.</span>
                </div>
            </div>
            <div style="padding:16px 18px; display:flex; justify-content:flex-end; gap:10px;">
                <button onclick="closeContactSuperAdminModal()" style="border:0; padding:10px 14px; border-radius:10px; background:#e5e7eb; color:#0b2540; font-weight:700;">Close</button>
            </div>
        </div>
    </div>
</body>
</html>
