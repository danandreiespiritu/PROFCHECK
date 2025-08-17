<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Class Schedule - PROFCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="#">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
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
            <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold {{ request()->is('admin/dashboard') ? 'bg-blue-100' : '' }}">
                <i class="fas fa-th-large text-lg"></i> Dashboard
            </a>

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

            <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold">
                <i class="fas fa-user text-lg"></i> User
            </a>

            <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold">
                <i class="fas fa-cog text-lg"></i> Settings
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-red-100 text-red-600 font-semibold w-full transition">
                    <i class="fas fa-sign-out-alt text-lg"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top Bar -->
        <header class="flex items-center justify-between bg-white px-8 py-4 border-b border-blue-100 shadow-sm sticky top-0 z-10">
            <div class="flex items-center gap-6">
                <button class="text-blue-600 hover:text-blue-800 focus:outline-none" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">View Class Schedule</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <div class="text-base font-semibold text-blue-900 leading-none">
                        {{ ucwords(Auth::user()->name ?? 'Guest') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard content -->
        <main class="p-6 space-y-8 overflow-auto bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen">
            <div class="max-w-screen mx-auto bg-white p-10 rounded-3xl shadow-2xl border border-blue-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-blue-800 tracking-tight mb-1">Class Schedules by Faculty</h2>
                    </div>
                    <a href="{{ url('admin/manageClassSchedule/addClassSched') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl shadow hover:from-blue-700 hover:to-blue-600 transition text-base font-semibold">
                        <i class="fas fa-plus"></i> Add Schedule
                    </a>
                </div>

                @forelse($groupedSchedules as $facultyName => $schedules)
                <section class="mb-14">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-blue-300 text-blue-700 text-3xl font-bold shadow-lg border-2 border-blue-200">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-blue-700">{{ $facultyName }}</h3>
                    </div>
                    <div class="overflow-x-auto rounded-2xl border border-blue-100 shadow-lg bg-gradient-to-br from-white to-blue-50">
                        <table class="min-w-full bg-transparent divide-y divide-blue-100">
                            <thead class="bg-blue-100 text-blue-900">
                                <tr>
                                    <th class="py-3 px-6 text-left font-semibold">Subject</th>
                                    <th class="py-3 px-6 text-left font-semibold">Section</th>
                                    <th class="py-3 px-6 text-left font-semibold">Year</th>
                                    <th class="py-3 px-6 text-left font-semibold">Day</th>
                                    <th class="py-3 px-6 text-left font-semibold">Start</th>
                                    <th class="py-3 px-6 text-left font-semibold">End</th>
                                    <th class="py-3 px-6 text-left font-semibold">Room</th>
                                    <th class="py-3 px-6 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-50">
                                @foreach($schedules as $schedule)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-3 px-6">{{ $schedule->subject }}</td>
                                    <td class="py-3 px-6">{{ $schedule->section }}</td>
                                    <td class="py-3 px-6">{{ $schedule->Yearlvl }}</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold">
                                            {{ $schedule->day_of_week }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="far fa-clock text-blue-400"></i>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="far fa-clock text-blue-400"></i>
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="inline-block px-2 py-1 rounded bg-blue-50 text-blue-800 text-xs font-medium">
                                            {{ $schedule->room }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ url('admin/manageClassSchedule/editClassSched/' . $schedule->id) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Delete Button (Triggers Modal) -->
                                            <button type="button"
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition"
                                                    onclick="openDeleteModal('{{ $schedule->id }}')"
                                                    title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div id="deleteModal-{{ $schedule->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                                                <div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm"></div>
                                                <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full space-y-6 border border-blue-100 animate-fade-in">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
                                                            <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                                                        </div>
                                                        <h2 class="text-2xl font-bold text-gray-800">Delete Schedule</h2>
                                                    </div>
                                                    <p class="text-gray-600 text-base">
                                                        Are you sure you want to delete this class schedule?
                                                        <br>
                                                        <span class="text-sm text-red-600 font-semibold">This action cannot be undone.</span>
                                                    </p>
                                                    <div class="flex justify-end gap-3 pt-2">
                                                        <button type="button"
                                                                onclick="closeDeleteModal('{{ $schedule->id }}')"
                                                                class="px-5 py-2 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 font-semibold transition shadow-sm border border-gray-200">
                                                            Cancel
                                                        </button>
                                                        <form action="{{ route('admin.manageClassSchedule.deleteClassSchedule', $schedule->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-semibold transition shadow-sm border border-red-600">
                                                                Confirm Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <style>
                                                @keyframes fade-in {
                                                    from { opacity: 0; transform: translateY(20px); }
                                                    to { opacity: 1; transform: translateY(0); }
                                                }
                                                .animate-fade-in {
                                                    animation: fade-in 0.25s ease;
                                                }
                                            </style>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
                @empty
                    <div class="flex flex-col items-center justify-center py-20">
                        <i class="fas fa-calendar-times text-6xl text-blue-200 mb-6"></i>
                        <p class="text-xl text-gray-500 font-medium">No class schedules found.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>
<script>
    function openDeleteModal(id) {
        document.getElementById('deleteModal-' + id).classList.remove('hidden');
    }

    function closeDeleteModal(id) {
        document.getElementById('deleteModal-' + id).classList.add('hidden');
    }
</script>
</html>
