<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <a href="{{ url('/') }}" class="flex items-center px-2 text-lg font-bold">RFID Attendance</a>
                @auth
                <a href="{{ auth()->user()->usertype === 'admin' ? route('admin.dashboard') : route('faculty.dashboard') }}" class="ml-6 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                @endauth
            </div>
            <div class="flex items-center">
                @auth
                    <span class="mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600">Logout</button></form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-blue-600">Login</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
