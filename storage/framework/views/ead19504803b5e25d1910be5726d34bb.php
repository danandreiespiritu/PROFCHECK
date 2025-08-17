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
                            <?php echo e(ucwords(Auth::user()->name ?? 'Guest')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </header>
   <!-- Dashboard content -->
    <main class="p-8 space-y-10 overflow-auto bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen">
        <div class="bg-white shadow-xl rounded-2xl p-8 border border-blue-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-500"></i>
                    Faculty Attendance Report
                </h2>
                <form action="<?php echo e(route('attendance.report.pdf')); ?>" method="GET" class="flex gap-2">
                    <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow">
                        <i class="fas fa-file-export"></i>
                        Export PDF
                    </button>
                </form>
            </div>
            <form action="<?php echo e(url('admin/attendance/attendanceReport')); ?>" method="GET" class="mb-8">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label for="start_date" class="block text-sm font-semibold text-blue-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            value="<?php echo e(request('start_date')); ?>"
                            class="block w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 shadow-sm transition">
                    </div>
                    <div class="flex-1 w-full">
                        <label for="end_date" class="block text-sm font-semibold text-blue-700 mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            value="<?php echo e(request('end_date')); ?>"
                            class="block w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 shadow-sm transition">
                    </div>
                    <button type="submit"
                            class="mt-4 md:mt-6 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 font-semibold shadow transition">
                        <i class="fas fa-search mr-2"></i>
                        Generate
                    </button>
                </div>
            </form>
            <div class="overflow-x-auto rounded-lg border border-blue-100 shadow">
                <table class="min-w-full bg-white rounded-lg">
                    <thead class="bg-gradient-to-r from-blue-100 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Faculty Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Class Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Year Level</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Section</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Time In</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Time Out</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-blue-50 hover:bg-blue-50 transition">
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap">
                                <?php echo e($record->faculty->FirstName ?? 'N/A'); ?> <?php echo e($record->faculty->LastName ?? ''); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap">
                                <?php echo e($record->classSchedule->subject ?? 'N/A'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap">
                                <?php echo e($record->classSchedule->Yearlvl ?? 'N/A'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap">
                                <?php echo e($record->classSchedule->section ?? 'N/A'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap"><?php echo e($record->date); ?></td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap"><?php echo e($record->time_in ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm text-blue-900 whitespace-nowrap"><?php echo e($record->time_out ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    <?php if($record->status === 'Present'): ?> bg-green-100 text-green-700
                                    <?php elseif($record->status === 'Late'): ?> bg-yellow-100 text-yellow-700
                                    <?php elseif($record->status === 'Absent'): ?> bg-red-100 text-red-700
                                    <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                    <?php echo e($record->status ?? 'N/A'); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-6">
                                <i class="fas fa-info-circle mr-2"></i>
                                No faculty data available for the selected date range.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
  </div>
 </body>
</html><?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/admin/attendance/attendanceReport.blade.php ENDPATH**/ ?>