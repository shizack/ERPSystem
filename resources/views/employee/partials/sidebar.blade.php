<aside class="sidebar">
    <div class="sidebar-header">
        <h3>Employee Portal</h3>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <a href="{{ route('employee.dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('employee.requisitions.index') ? 'active' : '' }}">
                <a href="{{ route('employee.requisitions.index') }}">
                    <i class="material-icons">assignment</i>
                    <span>My Requisitions</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('employee.requisitions.create') ? 'active' : '' }}">
                <a href="{{ route('employee.requisitions.create') }}">
                    <i class="material-icons">add_circle_outline</i>
                    <span>New Requisition</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
