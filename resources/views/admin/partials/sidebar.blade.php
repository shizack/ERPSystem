<aside class="sidebar">
    <div class="sidebar-header">
        <h3>ERP System</h3>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <a href="{{ route('admin.inventory.index') }}">
                    <i class="material-icons">inventory_2</i>
                    <span>Inventory</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.requisitions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.requisitions.all') }}">
                    <i class="material-icons">assignment</i>
                    <span>Requisitions</span>
                </a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </nav>
</aside>
