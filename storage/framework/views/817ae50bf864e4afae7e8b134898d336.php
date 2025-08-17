<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Logs | PROFCHECK</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
   <nav class="bg-blue-400 shadow-md mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo e(route('faculty.dashboard')); ?>" class="flex items-center text-blue-700 font-extrabold text-xl tracking-wide mr-8">
                        PROFCHECK
                    </a>
                    <div class="hidden sm:flex sm:space-x-6">
                        <a href="<?php echo e(route('faculty.dashboard')); ?>" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium <?php echo e(request()->routeIs('faculty.dashboard') ? 'text-blue-700' : ''); ?>">Dashboard</a>
                        <a href="<?php echo e(route('faculty.attendance.logs')); ?>" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium <?php echo e(request()->routeIs('faculty.attendance.logs') ? 'text-blue-700' : ''); ?>">Attendance Logs</a>
                        <a href="<?php echo e(route('faculty.profile')); ?>" class="inline-flex items-center px-2 py-1 rounded hover:bg-blue-100 transition text-gray-700 font-medium <?php echo e(request()->routeIs('faculty.profile') ? 'text-blue-700' : ''); ?>">Profile</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="hidden sm:inline text-gray-600 mr-4 font-semibold"><?php echo e(auth()->user()->name ?? auth()->user()->email); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-white bg-red-500 hover:bg-red-600 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-blue-700 mb-6 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3M16 7V3M4 11h16M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Attendance Logs
            </h1>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Time In</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Time Out</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="px-4 py-3"><?php echo e(\Carbon\Carbon::parse($log->date)->format('M d, Y')); ?></td>
                            <td class="px-4 py-3"><?php echo e($log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : '-'); ?></td>
                            <td class="px-4 py-3"><?php echo e($log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : '-'); ?></td>
                            <td class="px-4 py-3">
                                <?php if($log->status === 'Present'): ?>
                                    <span class="inline-block px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">Present</span>
                                <?php elseif($log->status === 'Late'): ?>
                                    <span class="inline-block px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded">Late</span>
                                <?php elseif($log->status === 'Absent'): ?>
                                    <span class="inline-block px-2 py-1 text-xs font-bold bg-red-100 text-red-700 rounded">Absent</span>
                                <?php else: ?>
                                    <span class="inline-block px-2 py-1 text-xs font-bold bg-gray-100 text-gray-700 rounded"><?php echo e($log->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?php echo e($log->classSchedule?->subject ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">No attendance logs found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center">
                <?php echo e($logs->links()); ?>

            </div>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/faculty/attendanceLog.blade.php ENDPATH**/ ?>