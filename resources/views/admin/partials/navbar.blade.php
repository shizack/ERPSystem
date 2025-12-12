<nav class="w-full bg-white shadow-sm border-b border-blue-100 px-8 py-4 rounded-bl-3xl 
        relative z-10">


    <div class="flex items-center justify-between">

        <!-- Left -->
        <div class="flex items-center gap-4">
            <button class="lg:hidden text-gray-600" @click="sidebarOpen = !sidebarOpen">
                <i class="material-icons text-[26px]">menu</i>
            </button>

            <h1 class="text-2xl font-bold text-[#0B3B87] tracking-tight">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        <!-- Right -->
        <div class="flex items-center gap-6">

            <!-- Notifications -->
            <div class="relative">
                <button class="relative text-gray-700 hover:text-blue-600 transition">
                    <i class="material-icons text-[26px]">notifications</i>
                </button>
                <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border border-white"></span>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">

                <!-- User Button -->
                <button @click="open = !open" class="flex items-center gap-3 group">

                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-300 to-yellow-300 
                                flex items-center justify-center text-gray-900 font-semibold shadow-sm">
                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                    </div>

                    <span class="font-medium text-gray-800">
                        {{ auth('admin')->user()->name }}
                    </span>

                    <i class="material-icons text-gray-500 group-hover:text-blue-600 transition">
                        arrow_drop_down
                    </i>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-lg border border-blue-100 py-2 z-50"
                    style="display: none;"
                >

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                            <i class="material-icons text-gray-400 mr-2 text-[18px]">logout</i>
                            Sign out
                        </button>
                    </form>
                </div>

            </div>
        </div>

