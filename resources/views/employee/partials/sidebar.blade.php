<aside class="sidebar w-64 bg-white min-h-screen border-r shadow-sm px-6 py-8">

    <h2 class="text-lg font-semibold text-gray-800 mb-8">Employee Portal</h2>

    <nav class="sidebar-nav">
        <ul class="space-y-3">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('employee.dashboard') }}"
                   class="flex items-center gap-3 text-gray-700 px-2 py-2 rounded-lg transition
                   {{ request()->routeIs('employee.dashboard') 
                       ? 'bg-blue-50 text-blue-600 font-semibold' 
                       : 'hover:bg-gray-100' }}">
                    <i class="material-icons text-[22px]">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- My Requisitions -->
            <li>
                <a href="{{ route('employee.requisitions.index') }}"
                   class="flex items-center gap-3 text-gray-700 px-2 py-2 rounded-lg transition
                   {{ request()->routeIs('employee.requisitions.index') 
                       ? 'bg-blue-50 text-blue-600 font-semibold'
                       : 'hover:bg-gray-100' }}">
                    <i class="material-icons text-[22px]">assignment</i>
                    <span>My Requisitions</span>
                </a>
            </li>

            <!-- New Requisition -->
            <li>
                <a href="{{ route('employee.requisitions.create') }}"
                   class="flex items-center gap-3 text-gray-700 px-2 py-2 rounded-lg transition
                   {{ request()->routeIs('employee.requisitions.create') 
                       ? 'bg-blue-50 text-blue-600 font-semibold'
                       : 'hover:bg-gray-100' }}">
                    <i class="material-icons text-[22px]">add_circle_outline</i>
                    <span>New Requisition</span>
                </a>
            </li>

        </ul>
    </nav>

</aside>
