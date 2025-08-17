<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen">
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

    <?php
        $faculty = $faculty ?? (auth()->check() ? auth()->user()->faculty : null);
    ?>

    <?php if(! $faculty): ?>
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto mt-16 border-l-4 border-blue-500">
            <h2 class="text-2xl font-semibold mb-2 text-blue-700">Faculty profile required</h2>
            <p class="text-gray-700">We couldn't find a faculty profile for your account. Please complete your faculty profile to view the dashboard.</p>
            <div class="mt-6">
                <a href="<?php echo e(route('profile.edit')); ?>" class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow transition">Complete Profile</a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-blue-500 hover:shadow-2xl transition">
                <div class="flex items-center gap-6">
                    <img src="<?php echo e(asset('storage/images/university-student6136.logowik.jpeg')); ?>" alt="avatar" class="w-24 h-24 rounded-full object-cover border-4 border-blue-200 shadow">
                    <div>
                        <h2 class="text-2xl font-bold text-blue-700"><?php echo e($faculty->FirstName); ?> <?php echo e($faculty->LastName); ?></h2>
                        <div class="text-sm text-gray-500 mb-2"><?php echo e($faculty->Position); ?></div>
                        <div class="mt-2 text-sm text-gray-600 space-y-1">
                            <div><strong>Email:</strong> <?php echo e($faculty->Email); ?></div>
                            <div><strong>RFID:</strong> <?php echo e($faculty->rfid_tag); ?></div>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow transition">Edit Profile</a>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col md:flex-row items-center justify-between hover:shadow-2xl transition">
                    <div>
                        <h3 class="text-xl font-semibold text-blue-700">Today's Attendance</h3>
                        <p class="text-sm text-gray-600">
                            Status for <?php echo e(now('Asia/Manila')->toFormattedDateString()); ?>

                        </p>
                        <?php
                            $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
                            $attendance = $faculty->attendances()
                                ->whereDate('date', $today)
                                ->first();
                        ?>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <?php if(isset($attendance) && $attendance->status === 'Present'): ?>
                            <span class="px-6 py-2 bg-green-100 text-green-700 rounded-full font-semibold shadow">Present</span>
                        <?php else: ?>
                            <span class="px-6 py-2 bg-red-100 text-red-700 rounded-full font-semibold shadow">Absent</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition">
                    <h3 class="text-xl font-semibold mb-4 text-blue-700">Class Schedule</h3>
                    <?php if(isset($schedules) && $schedules->isEmpty()): ?>
                        <div class="text-gray-500">No scheduled classes.</div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php $__currentLoopData = $schedules ?? $faculty->classSchedules()->orderBy('day_of_week')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-5 border rounded-xl bg-gray-50 hover:bg-blue-50 transition">
                                    <div class="font-semibold text-blue-700"><?php echo e($sched->subject); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo e($sched->section); ?> &middot; Year <?php echo e($sched->Yearlvl); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo e($sched->day_of_week); ?> • <?php echo e(\Carbon\Carbon::parse($sched->start_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($sched->end_time)->format('h:i A')); ?></div>
                                    <div class="text-sm text-gray-600">Room: <?php echo e($sched->room); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-blue-700">Recent Attendance Logs</h3>
                        <a href="<?php echo e(route('faculty.attendance.logs')); ?>" class="text-sm text-blue-600 hover:underline">View all</a>
                    </div>
                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $recentLogs ?? $faculty->attendances()->with('classSchedule')->orderByDesc('date')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-4 flex items-center justify-between border rounded-xl bg-gray-50 hover:bg-blue-50 transition">
                                <div>
                                    <div class="font-semibold text-blue-700"><?php echo e($log->date->toFormattedDateString()); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo e($log->classSchedule?->subject ?? '—'); ?></div>
                                </div>
                                <div>
                                    <?php if($log->status === 'Present'): ?>
                                        <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full font-semibold shadow">Present</span>
                                    <?php else: ?>
                                        <span class="px-4 py-1 bg-red-100 text-red-700 rounded-full font-semibold shadow">Absent</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-gray-500">No recent attendance logs.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/faculty/dashboard.blade.php ENDPATH**/ ?>