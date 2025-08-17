<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Admin Dashboard - PROFCHECK
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
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
            <a href="<?php echo e(url('admin/dashboard')); ?>" class="flex items-center text-center gap-2 text-2xl font-extrabold text-blue-700 tracking-wide">
                PROFCHECK
            </a>
        </header>

        <!-- Navigation -->
        <nav class="flex flex-col px-6 py-8 space-y-4 text-base font-medium text-blue-900">
            <!-- Dashboard Link -->
            <a href="<?php echo e(url('admin/dashboard')); ?>" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold <?php echo e(request()->is('admin/dashboard') ? 'bg-blue-100' : ''); ?>">
                <i class="fas fa-th-large text-lg"></i>
                Dashboard
            </a>

            <!-- Dropdown Item Component -->
            <?php
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
            ?>

            <?php $__currentLoopData = $dropdowns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center justify-between w-full py-2 px-3 rounded-lg hover:bg-blue-100 transition font-semibold focus:outline-none">
                    <div class="flex items-center gap-3">
                        <i class="fas <?php echo e($dropdownIcons[$label] ?? 'fa-box-open'); ?> text-lg"></i>
                        <?php echo e($label); ?>

                    </div>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-xs text-blue-400"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="mt-2 bg-white rounded-lg shadow-lg border border-blue-100 overflow-hidden">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$text, $link]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(url($link)); ?>" class="block px-6 py-2 text-sm hover:bg-blue-50 text-blue-800 transition"><?php echo e($text); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-red-100 text-red-600 font-semibold w-full transition">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    Logout
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="flex items-center justify-between bg-white px-8 py-4 border-b border-blue-100 shadow-sm sticky top-0 z-10">
            <div class="flex items-center gap-6">
                <button class="text-blue-600 hover:text-blue-800 focus:outline-none" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">Dashboard</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="text-right">
                        <div class="text-base font-semibold text-blue-900 leading-none">
                            <?php echo e(ucwords(Auth::user()->name ?? 'Guest')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </header>
   <!-- Dashboard content -->
    <main class="p-4 md:p-8 bg-[#f8fafc] flex-1 overflow-auto">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg border border-blue-100 p-6 md:p-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-blue-800 mb-1">Admin Dashboard</h2>
                    <p class="text-blue-400 text-sm">Manage your attendance system efficiently.</p>
                </div>
            </div>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <!-- Total Faculty -->
                <div class="flex items-center bg-gradient-to-r from-blue-100 to-blue-50 rounded-xl p-5 shadow hover:shadow-md transition">
                    <div class="flex-shrink-0 bg-blue-200 rounded-full p-3 mr-4">
                        <i class="fas fa-user-tie text-2xl text-blue-700"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-blue-800"><?php echo e($facultyCount ?? 0); ?></div>
                        <div class="text-blue-500 text-xs">Total Faculty</div>
                    </div>
                </div>
                <!-- Faculty Attended Today -->
                <div class="flex items-center bg-gradient-to-r from-yellow-100 to-yellow-50 rounded-xl p-5 shadow hover:shadow-md transition">
                    <div class="flex-shrink-0 bg-yellow-200 rounded-full p-3 mr-4">
                        <i class="fas fa-calendar-check text-2xl text-yellow-700"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-yellow-800"><?php echo e($todayAttendance ?? 0); ?></div>
                        <div class="text-yellow-500 text-xs">Faculty Attended Today</div>
                    </div>
                </div>
                <!-- New Widget: Classes Scheduled Today -->
                <div class="flex items-center bg-gradient-to-r from-green-100 to-green-50 rounded-xl p-5 shadow hover:shadow-md transition">
                    <div class="flex-shrink-0 bg-green-200 rounded-full p-3 mr-4">
                        <i class="fas fa-chalkboard-teacher text-2xl text-green-700"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-green-800"><?php echo e($classesScheduledToday ?? 0); ?></div>
                        <div class="text-green-500 text-xs">Classes Scheduled Today</div>
                    </div>
                </div>
                <!-- New Widget: Late Arrivals Today -->
                <div class="flex items-center bg-gradient-to-r from-red-100 to-red-50 rounded-xl p-5 shadow hover:shadow-md transition">
                    <div class="flex-shrink-0 bg-red-200 rounded-full p-3 mr-4">
                        <i class="fas fa-clock text-2xl text-red-700"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-red-800"><?php echo e($lateArrivalsToday ?? 0); ?></div>
                        <div class="text-red-500 text-xs">Late Arrivals Today</div>
                    </div>
                </div>
            </div>

            <!-- Custom Analytics Section (No Graphs) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Attendance Summary Table -->
                <div class="bg-blue-50 rounded-xl p-6 shadow border">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Attendance Summary (Last 7 Days)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-blue-900">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">Day</th>
                                    <th class="px-3 py-2 text-left font-semibold">Present</th>
                                    <th class="px-3 py-2 text-left font-semibold">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $trendLabels = $attendanceTrendLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                                    $trendData = $attendanceTrendData ?? [30, 45, 50, 40, 60, 55, 70];
                                    $facultyTotal = $facultyCount ?? 0;
                                ?>
                                <?php $__currentLoopData = $trendLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b last:border-0">
                                        <td class="px-3 py-2"><?php echo e($day); ?></td>
                                        <td class="px-3 py-2"><?php echo e($trendData[$i] ?? 0); ?></td>
                                        <td class="px-3 py-2"><?php echo e(max(0, $facultyTotal - ($trendData[$i] ?? 0))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- New Faculty Attendance Rate Card -->
                <div class="bg-white rounded-xl p-6 shadow border flex flex-col justify-center items-center">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Faculty Attendance Rate</h3>
                    <canvas id="facultyAttendancePie" width="120" height="120"></canvas>
                    <?php
                        $rate = $facultyAttendanceRate ?? ['Present' => 80, 'Absent' => 20];
                        $present = $rate['Present'] ?? 0;
                        $absent = $rate['Absent'] ?? 0;
                        $total = $present + $absent;
                        $percent = $total > 0 ? round(($present / $total) * 100) : 0;
                    ?>
                    <div class="mt-4 flex flex-col items-center">
                        <span class="text-2xl font-bold text-blue-700"><?php echo e($percent); ?>%</span>
                        <span class="text-blue-500 text-xs">Present Rate</span>
                        <div class="flex gap-4 mt-2 text-xs">
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                Present: <?php echo e($present); ?>

                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span>
                                Absent: <?php echo e($absent); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More widgets: Recent Faculty Activity & Upcoming Schedules -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Recent Faculty Activity -->
                <div class="bg-white rounded-xl p-6 shadow border">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Recent Faculty Activity</h3>
                    <ul class="divide-y divide-blue-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentFacultyActivity ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="py-2 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-400"></i>
                                <span class="font-semibold text-blue-800"><?php echo e($activity['name'] ?? 'Unknown'); ?></span>
                                <span class="text-xs text-blue-400 ml-2"><?php echo e($activity['action'] ?? ''); ?></span>
                                <span class="ml-auto text-xs text-blue-300"><?php echo e($activity['time'] ?? ''); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="py-2 text-blue-400">No recent activity.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- Upcoming Schedules -->
                <div class="bg-blue-50 rounded-xl p-6 shadow border">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Upcoming Schedules</h3>
                    <ul class="divide-y divide-blue-100">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingSchedules ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="py-2 flex flex-col">
                                <span class="font-semibold text-blue-800"><?php echo e($sched['subject'] ?? 'Class'); ?></span>
                                <span class="text-xs text-blue-400"><?php echo e($sched['faculty'] ?? ''); ?></span>
                                <span class="text-xs text-blue-300"><?php echo e($sched['time'] ?? ''); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="py-2 text-blue-400">No upcoming schedules.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    <script>
        // Example data, replace with dynamic data as needed
        const attendanceTrendLabels = <?php echo json_encode($attendanceTrendLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']); ?>;
        const attendanceTrendData = <?php echo json_encode($attendanceTrendData ?? [30, 45, 50, 40, 60, 55, 70]); ?>;
        const facultyAttendanceRate = <?php echo json_encode($facultyAttendanceRate ?? ['Present' => 80, 'Absent' => 20]); ?>;

        // Attendance Trend Line Chart
        const ctxTrend = document.getElementById('attendanceTrendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: attendanceTrendLabels,
                datasets: [{
                    label: 'Attendance',
                    data: attendanceTrendData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Faculty Attendance Pie Chart
        const ctxPie = document.getElementById('facultyAttendancePie').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: Object.keys(facultyAttendanceRate),
                datasets: [{
                    data: Object.values(facultyAttendanceRate),
                    backgroundColor: ['#22c55e', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
  </div>
 </body>
  <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebar', () => ({
            open: false,
            toggle() {
                this.open = !this.open;
            }
        }));
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</html><?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>