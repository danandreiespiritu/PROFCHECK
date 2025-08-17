<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
    <nav class="bg-blue-400 shadow-md mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('faculty.dashboard') }}" class="flex items-center text-blue-700 font-extrabold text-xl tracking-wide mr-8">
                        PROFCHECK
                    </a>
                    <div class="hidden sm:flex sm:space-x-6">
                        <a href="{{ route('faculty.dashboard') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium {{ request()->routeIs('faculty.dashboard') ? 'text-blue-700' : '' }}">Dashboard</a>
                        <a href="{{ route('faculty.attendance.logs') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium {{ request()->routeIs('faculty.attendance.logs') ? 'text-blue-700' : '' }}">Attendance Logs</a>
                        <a href="{{ route('faculty.profile') }}" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium {{ request()->routeIs('faculty.profile') ? 'text-blue-700' : '' }}">Profile</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="hidden sm:inline text-gray-600 mr-4 font-semibold">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-white bg-red-500 hover:bg-red-600 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    @php
        $faculty = $faculty ?? (auth()->check() ? auth()->user()->faculty : null);
    @endphp

    <div class="flex flex-col items-center justify-center min-h-[80vh] px-4">
        @if(! $faculty)
            <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full mt-10">
                <h2 class="text-2xl font-bold mb-3 text-blue-700">Faculty Profile Required</h2>
                <p class="text-gray-700 mb-6">We couldn't find a faculty profile for your account. Please complete your faculty profile to view the dashboard.</p>
                <a href="{{ route('profile.edit') }}" class="w-full block text-center px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Complete Profile</a>
            </div>
        @else
            <div class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center">
                    <img src="{{ asset('storage/images/university-student6136.logowik.jpeg') }}" alt="avatar" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200 shadow mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $faculty->FirstName }} {{ $faculty->LastName }}</h2>
                    <div class="text-blue-600 font-semibold mb-2">{{ $faculty->Position }}</div>
                    <div class="w-full mt-4 space-y-2 text-sm text-gray-600">
                        <div class="flex items-center"><span class="font-semibold w-20">Email:</span> <span class="truncate">{{ $faculty->Email }}</span></div>
                        <div class="flex items-center"><span class="font-semibold w-20">RFID:</span> <span>{{ $faculty->rfid_tag }}</span></div>
                    </div>
                </div>
                <!-- Dashboard Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 lg:col-span-2 flex flex-col justify-center">
                    <h2 class="text-3xl font-extrabold text-blue-700 mb-4">Welcome to your dashboard</h2>
                    <p class="text-gray-700 mb-8">Here you can manage your attendance, view logs, and update your profile.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('faculty.attendance.logs') }}" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold text-center hover:bg-blue-700 transition">View Attendance Logs</a>
                        <a href="{{ route('profile.edit') }}" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-semibold text-center hover:bg-green-700 transition">Edit Profile</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Responsive Nav for Mobile -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-inner z-50">
        <div class="flex justify-around py-2">
            <a href="{{ route('faculty.dashboard') }}" class="flex flex-col items-center text-blue-700 hover:text-blue-900 text-xs">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5 0a2 2 0 002-2V7a2 2 0 00-2-2h-3.5a2 2 0 01-1.5-.67A2 2 0 0012 4a2 2 0 00-1.5.33A2 2 0 019 5H5a2 2 0 00-2 2v11a2 2 0 002 2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('faculty.attendance.logs') }}" class="flex flex-col items-center text-blue-700 hover:text-blue-900 text-xs">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                Logs
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center text-blue-700 hover:text-blue-900 text-xs">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Profile
            </a>
        </div>
    </div>
</body>
</html>
