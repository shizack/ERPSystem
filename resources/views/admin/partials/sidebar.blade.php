<aside class="sidebar">
    <div class="sidebar-header">
        <h3>Admin Portal</h3>
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
            <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">
                    <i class="material-icons">shopping_cart</i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.requisitions.*') || request()->routeIs('admin.requisitions.all') ? 'active' : '' }}">
                <a href="{{ route('admin.requisitions.all') }}">
                    <i class="material-icons">assignment</i>
                    <span>Requisitions</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.purchase-orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchase-orders.index') }}">
                    <i class="material-icons">receipt_long</i>
                    <span>Purchase Orders</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
