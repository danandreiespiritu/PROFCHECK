<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class Schedule - PROFCHECK</title>
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
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">Manage Class Schedule</h1>
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
    <main class="p-6 space-y-8 overflow-auto bg-[#f8fafc] min-h-screen">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-blue-800 mb-6">Add Class Schedule</h2>
            <form action="{{ route('admin.manageClassSchedule.updateClassSchedule', $schedule->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') <!-- Use PUT method for updating -->
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Faculty ID -->
                    <div>
                        <label for="faculty_ID" class="block text-blue-900 font-semibold mb-2">Faculty</label>
                        <select name="faculty_ID" id="faculty_ID" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Select Faculty</option>
                            @foreach($faculties ?? [] as $faculty)
                                <option value="{{ $faculty->Faculty_ID }}"
                                    @if(old('faculty_ID', $schedule->faculty_ID) == $faculty->Faculty_ID) selected @endif>
                                    {{ $faculty->FirstName }} {{ $faculty->LastName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-blue-900 font-semibold mb-2">Course/Subject Description:</label>
                        <input value="{{ old('subject', $schedule->subject) }}" type="text" name="subject" id="subject" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>

                    <!-- Year Level -->
                    <div>
                        <label for="Yearlvl" class="block text-blue-900 font-semibold mb-2">Year level</label>
                        <select name="Yearlvl" id="Yearlvl" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Select Year level</option>
                            @php
                                $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                $selectedYear = old('Yearlvl', $schedule->Yearlvl ?? '');
                            @endphp
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Section -->
                    <div>
                        <label for="section" class="block text-blue-900 font-semibold mb-2">Section</label>
                        <select name="section" id="section" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Select Section</option>
                            @php
                                $sections = ['A', 'B', 'C', 'D'];
                                $selectedSection = old('section', $schedule->section ?? '');
                            @endphp
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ $selectedSection == $section ? 'selected' : '' }}>{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Day of Week -->
                    <div>
                        <label for="day_of_week" class="block text-blue-900 font-semibold mb-2">Day of Week</label>
                        <select name="day_of_week" id="day_of_week" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Select Day</option>
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $selectedDay = old('day_of_week', $schedule->day_of_week ?? '');
                            @endphp
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-blue-900 font-semibold mb-2">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('start_time', $schedule->start_time) }}" required>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-blue-900 font-semibold mb-2">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('end_time', $schedule->end_time) }}" required>
                    </div>

                    <!-- Room -->
                    <div>
                        <label for="room" class="block text-blue-900 font-semibold mb-2">Room</label>
                        <input type="text" value="{{ old('room', $schedule->room) }}" name="room" id="room" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-800 transition">Update Class Schedule</button>
                </div>
                @if(session('success'))
                    <div class="mb-6">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </main>
  </div>
 </body>
</html>