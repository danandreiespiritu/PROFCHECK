<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty - PROFCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="#">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        font-family: 'Inter', sans-serif;
        }
    </style>
</head>
 <body x-data="{ sidebarOpen: true }" class="bg-[#f0f5f8] min-h-screen flex">
    <!-- Sidebar -->
    <aside x-show="sidebarOpen" class="bg-gradient-to-b from-blue-50 to-white w-64 min-h-screen flex flex-col border-r border-blue-100 shadow-lg z-20" x-transition>
        <!-- Header -->
        <header class="flex items-center justify-center py-8 border-b border-blue-100">
            <a href="{{ url('admin/dashboard') }}" class="flex items-center text-center gap-2 text-2xl font-extrabold text-blue-700 tracking-wide">
                PROFCHECK
            </a>
        </header>

        <!-- Navigation -->
        <nav class="flex flex-col px-6 py-8 space-y-4 text-base font-medium text-blue-900">
            <!-- Dashboard Link -->
            <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold {{ request()->is('admin/dashboard') ? 'bg-blue-100' : '' }}">
                <i class="fas fa-th-large text-lg"></i>
                Dashboard
            </a>

            <!-- Dropdown Item Component -->
            @php
                $dropdowns = [
                    'Faculty' => [
                        ['Add Faculty', 'admin/manageFaculty/addFaculty'],
                        ['View Faculty', 'admin/manageFaculty/viewFaculty'],
                    ],
                    'Attendance' => [
                        ['Daily Attendance', 'admin/attendance/dailyAttendance'],
                        ['Attendance Report', 'admin/attendance/attendanceReport'],
                        ['Attendance Summary', '#'],
                    ],
                     'Schedule' => [
                        ['Add Class Schedule', 'admin/manageClassSchedule/addClassSched'],
                        ['View Class Schedule', 'admin/manageClassSchedule/viewClassSched'],
                    ],
                ];
                $dropdownIcons = [
                    'Faculty' => 'fa-user-tie',
                    'Attendance' => 'fa-calendar-check',
                    'Schedule' => 'fa-calendar-alt',
                ];
            @endphp

            @foreach ($dropdowns as $label => $items)
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center justify-between w-full py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold focus:outline-none">
                    <div class="flex items-center gap-3">
                        <i class="fas {{ $dropdownIcons[$label] ?? 'fa-box-open' }} text-lg"></i>
                        {{ $label }}
                    </div>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-xs text-blue-400"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="mt-2 bg-white rounded-lg shadow-lg border border-blue-100 overflow-hidden">
                    @foreach ($items as [$text, $link])
                    <a href="{{ url($link) }}" class="block px-6 py-2 text-sm hover:bg-blue-50 text-blue-800 transition">{{ $text }}</a>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- User -->
            <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold">
                <i class="fas fa-user text-lg"></i>
                User
            </a>

            <!-- Settings -->
            <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold">
                <i class="fas fa-cog text-lg"></i>
                Settings
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-red-100 text-red-600 font-semibold w-full transition">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    Logout
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top bar -->
        <header class="flex items-center justify-between bg-white px-8 py-4 border-b border-blue-100 shadow-sm sticky top-0 z-10">
            <div class="flex items-center gap-6">
                <button class="text-blue-600 hover:text-blue-800 focus:outline-none" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">Manage Faculty</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="text-right">
                        <div class="text-base font-semibold text-blue-900 leading-none">
                            {{ ucwords(Auth::user()->name ?? 'Guest') }}
                        </div>
                    </div>
                </div>
            </div>
        </header>
   <!-- Dashboard content -->
    <main class="p-8 space-y-10 overflow-auto bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-10 rounded-3xl shadow-2xl border border-blue-100">
            <div class="flex items-center gap-4 mb-10">
                <div class="bg-gradient-to-br from-blue-200 to-blue-400 text-blue-800 rounded-full p-4 shadow">
                    <i class="fas fa-user-tie text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-blue-900 tracking-tight">Edit Faculty</h2>
            </div>

            <form action="{{ route('admin.manageFaculty.editFacultyPatch', $faculty->Faculty_ID) }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="FirstName" class="block text-sm font-semibold text-blue-900 mb-2">First Name</label>
                        <input type="text" id="FirstName" name="FirstName" required
                            value="{{ old('FirstName', $faculty->FirstName) }}"
                            class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition placeholder-gray-400 bg-blue-50 focus:bg-white"
                            placeholder="Enter first name" autocomplete="off">
                    </div>
                    <div>
                        <label for="LastName" class="block text-sm font-semibold text-blue-900 mb-2">Last Name</label>
                        <input type="text" id="LastName" name="LastName" required
                            value="{{ old('LastName', $faculty->LastName) }}"
                            class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition placeholder-gray-400 bg-blue-50 focus:bg-white"
                            placeholder="Enter last name" autocomplete="off">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="rfid_tag" class="block text-sm font-semibold text-blue-900 mb-2">RFID Card</label>
                        <div class="relative">
                            <input type="text" id="rfid_tag" name="rfid_tag" required
                                value="{{ old('rfid_tag', $faculty->rfid_tag) }}"
                                class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition placeholder-gray-400 bg-blue-50 focus:bg-white pr-10"
                                placeholder="Scan or enter RFID tag" autocomplete="off">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label for="Email" class="block text-sm font-semibold text-blue-900 mb-2">Email</label>
                        <div class="relative">
                            <input type="email" id="Email" name="Email" required
                                value="{{ old('Email', $faculty->Email) }}"
                                class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition placeholder-gray-400 bg-blue-50 focus:bg-white pr-10"
                                placeholder="Enter email address" autocomplete="off">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="Position" class="block text-sm font-semibold text-blue-900 mb-2">Position</label>
                        <input type="text" id="Position" name="Position" required
                            value="{{ old('Position', $faculty->Position) }}"
                            class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition placeholder-gray-400 bg-blue-50 focus:bg-white"
                            placeholder="Enter position" autocomplete="off">
                    </div>
                    <div>
                        <label for="Gender" class="block text-sm font-semibold text-blue-900 mb-2">Gender</label>
                        <select id="Gender" name="Gender" required
                            class="block w-full border border-blue-200 rounded-xl shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 transition bg-blue-50 focus:bg-white">
                            <option value="" disabled>Select gender</option>
                            <option value="Male" {{ old('Gender', $faculty->Gender) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('Gender', $faculty->Gender) === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('Gender', $faculty->Gender) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white py-3 px-6 rounded-xl font-bold text-lg shadow-lg hover:from-blue-700 hover:to-blue-500 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
  </div>
 </body>
</html>