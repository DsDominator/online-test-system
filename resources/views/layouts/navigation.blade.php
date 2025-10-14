<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Left: Logo + Menu -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('student.exams.list') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                <!-- Navigation Links -->
                <x-nav-link :href="route('student.exams.list')" :active="request()->routeIs('student.exams.*')">
                    Bài thi
                </x-nav-link>

                @auth
                    @if(auth()->user()->is_admin ?? false)
                        <x-nav-link :href="route('admin.exams.index')" :active="request()->routeIs('admin.exams.*')">
                            Quản trị
                        </x-nav-link>
                        <x-nav-link :href="route('admin.results.index')" :active="request()->routeIs('admin.results.*')">
                            Kết quả
                        </x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- Right: Username + Logout -->
            @auth
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">
                        👋 {{ Auth::user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-md transition">
                            🔒 Đăng xuất
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>


