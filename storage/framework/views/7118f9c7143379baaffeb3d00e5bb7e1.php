<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - PROFCHECK</title>
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
                <h1 class="text-2xl font-bold text-blue-800 tracking-tight">Manage Faculty</h1>
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
    <main class="p-6 space-y-8 overflow-auto bg-gradient-to-br from-blue-50 to-white min-h-screen">
        <!-- View Faculties -->
        <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-full mx-auto border border-blue-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <h2 class="text-3xl font-extrabold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-user-tie text-blue-500"></i>
                    Faculty List
                </h2>
                <div class="flex gap-2 w-full md:w-auto">
                    <input type="text" placeholder="Search faculty..." class="border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 transition w-full md:w-72 shadow-sm" />
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto rounded-xl border border-blue-100">
                <table class="min-w-full divide-y divide-blue-100 text-sm">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">Faculty ID</th>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">Full Name</th>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">Position</th>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">RFID Tag</th>
                            <th class="px-5 py-3 text-left font-semibold text-blue-700 uppercase tracking-wider">Gender</th>
                            <th class="px-5 py-3 text-center font-semibold text-blue-700 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-50">
                        <?php $__empty_1 = true; $__currentLoopData = $faculty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-blue-50 transition group">
                            <td class="px-5 py-4 whitespace-nowrap text-blue-900 font-semibold"><?php echo e($faculties->Faculty_ID); ?></td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-blue-900"><?php echo e($faculties->FirstName); ?> <?php echo e($faculties->LastName); ?></span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-blue-700"><?php echo e($faculties->Email); ?></td>
                            <td class="px-5 py-4 whitespace-nowrap text-blue-700"><?php echo e($faculties->Position); ?></td>
                            <td class="px-5 py-4 whitespace-nowrap text-blue-700"><?php echo e($faculties->rfid_tag); ?></td>
                            <td class="px-5 py-4 whitespace-nowrap text-blue-700"><?php echo e($faculties->Gender); ?></td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="<?php echo e(route('admin.manageFaculty.editFaculty', $faculties->Faculty_ID)); ?>"
                                       class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 hover:text-blue-900 font-semibold shadow-sm transition"
                                       title="Edit Faculty">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden md:inline">Edit</span>
                                    </a>

                                    <!-- Delete Button (Triggers Modal) -->
                                    <button type="button"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 border border-red-200 text-red-700 hover:bg-red-100 hover:text-red-900 font-semibold shadow-sm transition"
                                            onclick="openDeleteModal('<?php echo e($faculties->Faculty_ID); ?>')"
                                            title="Delete Faculty">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="hidden md:inline">Delete</span>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div id="deleteModal-<?php echo e($faculties->Faculty_ID); ?>" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm"></div>
                                    <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full space-y-6 border border-blue-100 animate-fade-in">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
                                                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                                            </div>
                                            <h2 class="text-2xl font-bold text-gray-800">Delete Faculty</h2>
                                        </div>
                                        <p class="text-gray-600 text-base">
                                            Are you sure you want to delete<br>
                                            <span class="font-semibold text-blue-700"><?php echo e($faculties->FirstName); ?> <?php echo e($faculties->LastName); ?></span>?
                                            <br>
                                            <span class="text-sm text-red-600 font-semibold">This action cannot be undone.</span>
                                        </p>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button"
                                                    onclick="closeDeleteModal('<?php echo e($faculties->Faculty_ID); ?>')"
                                                    class="px-5 py-2 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 font-semibold transition shadow-sm border border-gray-200">
                                                Cancel
                                            </button>
                                            <form action="<?php echo e(route('admin.manageFaculty.deleteFaculty', $faculties->Faculty_ID)); ?>"
                                                  method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
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
                                        from { opacity: 0; transform: translateY(20px);}
                                        to { opacity: 1; transform: translateY(0);}
                                    }
                                    .animate-fade-in {
                                        animation: fade-in 0.25s ease;
                                    }
                                </style>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-blue-400">No faculty found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
</html><?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/admin/manageFaculty/viewFaculty.blade.php ENDPATH**/ ?>