@forelse($attendances as $attendance)
<tr class="hover:bg-blue-50 transition">
    <td class="px-4 py-3 text-blue-900 font-semibold">{{ $loop->iteration }}</td>
    <td class="px-4 py-3 text-blue-900">
        {{ isset($attendance->faculty) ? ($attendance->faculty->FirstName . ' ' . $attendance->faculty->LastName) : 'N/A' }}
    </td>
    <td class="px-4 py-3 text-blue-900">{{ $attendance->rfid_tag }}</td>
    <td class="px-4 py-3 text-blue-900">{{ $attendance->classSchedule->subject ?? 'N/A' }}</td>
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
