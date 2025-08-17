<nav class="bg-white border-b shadow-sm px-4 py-3">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-700">PROFCHECK</a>
            <form action="{{ Route::has('search') ? route('search') : url('/') }}" method="GET" class="hidden md:block">
                <input type="search" name="q" placeholder="Search" class="border rounded px-2 py-1 text-sm" />
            </form>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-blue-600">Login</a>
            @endauth
        </div>
    </div>
</nav>