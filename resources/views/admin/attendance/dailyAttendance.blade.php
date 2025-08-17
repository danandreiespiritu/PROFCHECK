<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Attendance - PROFCHECK</title>
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
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">Daily Attendance</h1>
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
        <div class="max-w-screen mx-auto bg-white p-10 rounded-2xl shadow-xl border border-blue-100">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-extrabold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-blue-500"></i>
                    Attendance Records for {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y') }}
                </h2>
                <a href="{{ url()->current() }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition text-sm font-semibold">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-blue-50">
                <!-- Vue mount point: AttendanceTable will hydrate here when built -->
                @php
                    $initialRows = $attendances->map(function($a) {
                        return [
                            'id' => $a->id,
                            'faculty_name' => ($a->faculty ? ($a->faculty->FirstName . ' ' . $a->faculty->LastName) : null),
                            'rfid_tag' => $a->rfid_tag,
                            'subject' => $a->classSchedule?->subject ?? null,
                            'date' => $a->date,
                            'time_in' => $a->time_in,
                            'time_out' => $a->time_out,
                            'status' => $a->status,
                        ];
                    })->toArray();
                @endphp
                <div id="vue-attendance-root"
                     data-poll-url="{{ route('admin.attendance.rows') }}"
                     data-initial-rows='@json($initialRows)'>
                    <!-- If JS loads, Vue will hydrate this with the reactive table. If not, the server-rendered table below remains visible as fallback. -->
                </div>
                <table class="min-w-full divide-y divide-blue-100 text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Faculty</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">RFID Tag</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Class Schedule</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Time In</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Time Out</th>
                            <th class="px-4 py-3 text-left font-bold text-blue-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50 bg-white">
                        @forelse($attendances as $attendance)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-4 py-3 text-blue-900 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-blue-900">
                                {{ isset($attendance->faculty) ? ($attendance->faculty->FirstName . ' ' . $attendance->faculty->LastName) : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-blue-900">{{ $attendance->rfid_tag }}</td>
                            <td class="px-4 py-3 text-blue-900">
                                {{ $attendance->classSchedule->subject ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-blue-900">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($attendance->time_in)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded font-mono">
                                        <i class="fas fa-sign-in-alt"></i>
                                        {{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($attendance->time_out)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded font-mono">
                                        <i class="fas fa-sign-out-alt"></i>
                                        {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($attendance->status === 'Present')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">
                                        <i class="fas fa-check-circle"></i> Present
                                    </span>
                                @elseif($attendance->status === 'Late')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-bold">
                                        <i class="fas fa-clock"></i> Late
                                    </span>
                                @elseif($attendance->status === 'Absent')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">
                                        <i class="fas fa-times-circle"></i> Absent
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-bold">
                                        <i class="fas fa-question-circle"></i> {{ $attendance->status ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-blue-400 text-lg">
                                <i class="fas fa-info-circle mr-2"></i>
                                No attendance records found for today.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="attendance-count" value="{{ $attendances->count() }}">
    <!-- Poll endpoint URL used by Vue component (and legacy polling) -->
    <input type="hidden" id="attendance-poll-url" value="{{ route('admin.attendance.rows') }}">
    </main>
  </div>
 </body>
    <script>
        // keep legacy polling as a fallback; Vue component will handle polling when available
        document.addEventListener('DOMContentLoaded', function () {
            let currentCount = parseInt(document.getElementById('attendance-count').value);
            const pollUrl = document.getElementById('attendance-poll-url')?.value || "{{ url('admin/attendance/dailyAttendance/count') }}";

            setInterval(() => {
                fetch(pollUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > currentCount) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching attendance count:', error);
                    });
            }, 5000); // Poll every 5 seconds
        });
    </script>
</html>