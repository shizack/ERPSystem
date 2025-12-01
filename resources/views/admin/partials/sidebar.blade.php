<aside class="sidebar w-64 bg-[#F5F9FF] border-r border-blue-100 h-screen px-6 py-6 shadow-md
        fixed top-0 left-0 z-50">

    <!-- Logo / Header -->
    <div class="flex items-center gap-3 mb-10">
        <img src="/images/logo.png" class="w-12 h-12 rounded-full shadow-sm" alt="">
        <h3 class="text-xl font-semibold text-[#0B3B87]">ERP System</h3>
    </div>

    <!-- Navigation -->
    <nav class="space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#0B3B87] font-medium transition
           {{ request()->routeIs('admin.dashboard') 
                ? 'bg-white shadow-sm border border-blue-200' 
                : 'hover:bg-blue-50 hover:text-blue-700' }}">
            <i class="material-icons text-[22px]">dashboard</i>
            <span>Dashboard</span>
        </a>

        <!-- Inventory -->
        <a href="{{ route('admin.inventory.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#0B3B87] font-medium transition
           {{ request()->routeIs('admin.inventory.*') 
                ? 'bg-white shadow-sm border border-blue-200' 
                : 'hover:bg-blue-50 hover:text-blue-700' }}">
            <i class="material-icons text-[22px]">inventory_2</i>
            <span>Inventory</span>
        </a>

        <!-- Requisitions -->
        <a href="{{ route('admin.requisitions.all') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#0B3B87] font-medium transition
           {{ request()->routeIs('admin.requisitions.*') 
                ? 'bg-white shadow-sm border border-blue-200' 
                : 'hover:bg-blue-50 hover:text-blue-700' }}">
            <i class="material-icons text-[22px]">assignment</i>
            <span>Requisitions</span>
        </a>

        <!-- Add more items below with the same format -->

    </nav>

</aside>
