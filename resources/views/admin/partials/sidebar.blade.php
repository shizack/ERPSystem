<aside class="sidebar" style="width:250px; height:calc(100vh - 36px); position:fixed; left:18px; top:18px; padding:20px; border-radius:14px; background:#ffffff; box-shadow:0 4px 16px rgba(0,0,0,0.06); z-index:20; display:flex; flex-direction:column; gap:12px; color:#333; overflow:auto;">
    <!-- Header / Brand -->
    <div class="sidebar-header" style="display:flex; justify-content:center !important; align-items:center; text-align:center; width:100%;">
        <div class="brand" style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; margin:0 auto;">
            <img src="{{ asset('images/logo.png') }}" alt="Mayet Resort Logo" style="width:46px; height:46px; border-radius:10px; object-fit:cover;">
            <div style="font-weight:800; font-size:1rem;">Mayet Resort</div>
        </div>
    </div>

    <!-- User Block -->
    <div class="user-info" style="margin-top:2px; padding:12px; border-radius:12px; background:#f8f9fa; display:flex; gap:12px; align-items:center; border:1px solid #e5e7eb; color:#333;">
        <div class="avatar" style="width:48px; height:48px; border-radius:9999px; display:grid; place-items:center; font-size:20px; color:#fff; background:linear-gradient(120deg,#007bff,#ffcc00); box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            {{ strtoupper(substr(optional(Auth::guard('admin')->user())->full_name ?? 'A',0,1)) }}
        </div>
        <div class="meta" style="font-size:0.9rem;">
            <div class="name" style="font-weight:700; color:#08203a;">{{ optional(Auth::guard('admin')->user())->full_name ?? 'Administrator' }}</div>
            <div class="role" style="font-size:0.8rem; color:#6b7280;">{{ optional(Auth::guard('admin')->user())->job_title ?? 'Administrator' }}</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-menu" aria-label="Main navigation" style="margin-top:6px; display:block;">
        <h4 style="margin:0 0 6px 2px; color:#6b7280; font-size:12px; font-weight:700; text-transform:uppercase;">Main</h4>
        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px;">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <a href="{{ route('admin.inventory.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">inventory</i>
                    <span>Inventory</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.purchase-orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchase-orders.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">shopping_bag</i>
                    <span>Purchase Orders</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.requisitions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.requisitions.all') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">list_alt</i>
                    <span>View Requisitions</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                <a href="{{ route('admin.suppliers.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">local_shipping</i>
                    <span>Suppliers</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                @if(optional(Auth::guard('admin')->user())->job_title === 'Administrator')
                <a href="{{ route('admin.users.create') }}" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">person_add</i>
                    <span>Create User</span>
                </a>
                @else
                <a href="#" onclick="event.preventDefault(); if(window.showContactSuperAdminModal){ showContactSuperAdminModal(); } else { alert('Contact Super Admin to create Users'); } return false;" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; color:#0f1724; font-weight:700; transition:all .18s ease;">
                    <i class="material-icons" style="color:#007bff; font-size:20px;">person_add</i>
                    <span>Create User</span>
                </a>
                @endif
            </li>
        </ul>

        <div class="group-sep" aria-hidden="true" style="height:1px; background:linear-gradient(90deg, rgba(2,6,23,0.04), rgba(2,6,23,0.02)); margin:10px 0; border-radius:2px;"></div>

        <h4 style="margin:0 0 6px 2px; color:#6b7280; font-size:12px; font-weight:700; text-transform:uppercase;">System</h4>
        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px;">
            <li>
                <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; text-decoration:none; font-weight:700; color:#ef4444;">
                    <i class="material-icons">logout</i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>
            </li>
        </ul>
    </nav>
</aside>
