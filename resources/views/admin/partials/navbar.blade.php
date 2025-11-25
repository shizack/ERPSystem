<nav class="top-nav">
    <div class="flex items-center justify-between p-4 bg-white shadow">
        <div class="flex items-center">
            <button class="text-gray-500 focus:outline-none lg:hidden" @click="sidebarOpen = !sidebarOpen">
                <i class="material-icons">menu</i>
            </button>
            <h1 class="ml-4 text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>
        
        <div class="flex items-center">
            <!-- Notifications -->
            <div class="relative mr-4">
                <button class="text-gray-500 focus:outline-none">
                    <i class="material-icons">notifications</i>
                </button>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </div>
            
            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    class="flex items-center focus:outline-none"
                >
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600">{{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}</span>
                    </div>
                    <span class="ml-2 text-gray-700">{{ auth('admin')->user()->name }}</span>
                    <i class="material-icons text-gray-500 ml-1">arrow_drop_down</i>
                </button>
                
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                    style="display: none;"
                >
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
