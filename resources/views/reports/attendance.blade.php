<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Attendance Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #1D4ED8;
            font-size: 24px;
            margin-bottom: 5px;
        }

        p {
            margin: 0 0 5px 0;
            color: #4B5563;
        }

        .date-range {
            font-weight: bold;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #1D4ED8;
            color: white;
        }

        th, td {
            border: 1px solid #E5E7EB;
            padding: 8px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #EFF6FF;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 10px;
        }

        .status-present {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .status-absent {
            background-color: #FECACA;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .status-late {
            background-color: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .no-records {
            text-align: center;
            padding: 30px 0;
            color: #6B7280;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Faculty Attendance Report</h1>
    <p>Generated on {{ now()->format('F d, Y') }}</p>

    @if($start_date && $end_date)
        <p>
            Attendance from 
            <span class="date-range">{{ \Carbon\Carbon::parse($start_date)->format('F d, Y') }}</span> 
            to 
            <span class="date-range">{{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}</span>
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Faculty Name</th>
                <th>Class Name</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ optional($attendance->faculty)->FirstName }} {{ optional($attendance->faculty)->LastName }}</td>
                    <td>{{ optional($attendance->classSchedule)->subject ?? 'N/A' }}</td>
                    <td>{{ optional($attendance->classSchedule)->Yearlvl ?? 'N/A' }}</td>
                    <td>{{ optional($attendance->classSchedule)->section ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                    <td>{{ $attendance->time_in ?? 'N/A' }}</td>
                    <td>{{ $attendance->time_out ?? 'N/A' }}</td>
                    <td>
                        <span class="status {{ strtolower($attendance->status) === 'present' ? 'status-present' : (strtolower($attendance->status) === 'absent' ? 'status-absent' : 'status-late') }}">
                            {{ $attendance->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
