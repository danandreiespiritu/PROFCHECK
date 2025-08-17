<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Attendance;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $faculty = $user->faculty; // relation from User to Faculty

        // If no faculty record exists for the authenticated user, redirect to profile edit
        if (! $faculty) {
            return redirect()->route('faculty.profile')->with('error', 'Faculty profile not found. Please complete your profile.');
        }

        $today = now('Asia/Manila')->toDateString();
        $attendance = Attendance::where('Faculty_ID', $faculty->Faculty_ID)
            ->whereDate('date', $today)
            ->first();

        // Fetch class schedules for this faculty
        $schedules = $faculty->classSchedules()->orderBy('day_of_week')->get();

        // Recent attendance logs (last 5)
        $recentLogs = Attendance::where('Faculty_ID', $faculty->Faculty_ID)
            ->with('classSchedule')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return view('faculty.dashboard', [
            'faculty' => $faculty,
            'attendance' => $attendance,
            'schedules' => $schedules,
            'recentLogs' => $recentLogs,
        ]);
    }

    public function attendanceLog()
    {
        $user = auth()->user();
        $faculty = $user->faculty;

        $logs = Attendance::where('Faculty_ID', $faculty->Faculty_ID)
            ->with('classSchedule')
            ->orderByDesc('date')
            ->paginate(20);

        return view('faculty.attendanceLog', compact('logs'));
    }
    public function profile()
    {
        $user = auth()->user();
        $faculty = $user->faculty;
        if (!$faculty) {
            return redirect()->route('faculty.profile')->with('error', 'Faculty profile not found. Please complete your profile.');
        }
        return view('faculty.profile', compact('faculty'));
    }
}
