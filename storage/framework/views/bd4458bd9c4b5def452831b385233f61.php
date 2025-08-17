<?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr class="hover:bg-blue-50 transition">
    <td class="px-4 py-3 text-blue-900 font-semibold"><?php echo e($loop->iteration); ?></td>
    <td class="px-4 py-3 text-blue-900">
        <?php echo e(isset($attendance->faculty) ? ($attendance->faculty->FirstName . ' ' . $attendance->faculty->LastName) : 'N/A'); ?>

    </td>
    <td class="px-4 py-3 text-blue-900"><?php echo e($attendance->rfid_tag); ?></td>
    <td class="px-4 py-3 text-blue-900"><?php echo e($attendance->classSchedule->subject ?? 'N/A'); ?></td>
    <td class="px-4 py-3 text-blue-900"><?php echo e(\Carbon\Carbon::parse($attendance->date)->format('M d, Y')); ?></td>
    <td class="px-4 py-3">
        <?php if($attendance->time_in): ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded font-mono">
                <i class="fas fa-sign-in-alt"></i>
                <?php echo e(\Carbon\Carbon::parse($attendance->time_in)->format('h:i A')); ?>

            </span>
        <?php else: ?>
            <span class="text-gray-400">-</span>
        <?php endif; ?>
    </td>
    <td class="px-4 py-3">
        <?php if($attendance->time_out): ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded font-mono">
                <i class="fas fa-sign-out-alt"></i>
                <?php echo e(\Carbon\Carbon::parse($attendance->time_out)->format('h:i A')); ?>

            </span>
        <?php else: ?>
            <span class="text-gray-400">-</span>
        <?php endif; ?>
    </td>
    <td class="px-4 py-3">
        <?php if($attendance->status === 'Present'): ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">
                <i class="fas fa-check-circle"></i> Present
            </span>
        <?php elseif($attendance->status === 'Late'): ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-bold">
                <i class="fas fa-clock"></i> Late
            </span>
        <?php elseif($attendance->status === 'Absent'): ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">
                <i class="fas fa-times-circle"></i> Absent
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-bold">
                <i class="fas fa-question-circle"></i> <?php echo e($attendance->status ?? 'N/A'); ?>

            </span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="8" class="px-4 py-10 text-center text-blue-400 text-lg">
        <i class="fas fa-info-circle mr-2"></i>
        No attendance records found for today.
    </td>
</tr>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem\resources\views/admin/attendance/partials/rows.blade.php ENDPATH**/ ?>