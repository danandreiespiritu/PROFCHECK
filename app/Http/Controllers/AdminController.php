<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function dashboard()
    {
        $todayDate = \Carbon\Carbon::now('Asia/Manila')->toDateString();
        $todayDay = strtolower(\Carbon\Carbon::now('Asia/Manila')->format('l'));

        $facultyCount = Faculty::count();

        $todayAttendance = Attendance::whereDate('date', $todayDate)
            ->where('status', 'Present')
            ->distinct('Faculty_ID')
            ->count('Faculty_ID');

        // Classes scheduled today
        $classesScheduledToday = ClassSchedule::where('day_of_week', $todayDay)->count();

        // Late arrivals (time_in after schedule start)
        $lateArrivalsToday = Attendance::whereDate('date', $todayDate)
            ->whereNotNull('time_in')
            ->whereExists(function ($query) use ($todayDay) {
                $query->select(\DB::raw(1))
                    ->from('class_schedules')
                    ->whereColumn('class_schedules.faculty_ID', 'attendance.Faculty_ID')
                    ->where('class_schedules.day_of_week', $todayDay)
                    ->whereRaw("attendance.time_in > class_schedules.start_time");
            })->count();

        // Recent activity (simple sample)
        $recentFacultyActivity = Attendance::with('faculty')->latest()->limit(6)->get()->map(function ($a) {
            return [
                'name' => $a->faculty?->FirstName . ' ' . $a->faculty?->LastName,
                'action' => $a->status,
                'time' => $a->created_at?->diffForHumans(),
            ];
        })->toArray();

        // Upcoming schedules (next 3 entries)
        $upcomingSchedules = ClassSchedule::with('faculty')->orderBy('day_of_week')->limit(3)->get()->map(function ($s) {
            return [
                'subject' => $s->subject,
                'faculty' => $s->faculty?->FirstName . ' ' . $s->faculty?->LastName,
                'time' => $s->start_time . ' - ' . $s->end_time,
            ];
        })->toArray();

        // Attendance trend
        $dates = collect(range(0, 6))->map(function ($i) {
            return \Carbon\Carbon::now('Asia/Manila')->subDays(6 - $i);
        });

        $attendanceTrendLabels = $dates->map(fn($date) => $date->format('D'))->toArray();
        $attendanceTrendData = $dates->map(function ($date) {
            return Attendance::whereDate('date', $date->toDateString())
                ->where('status', 'Present')
                ->distinct('Faculty_ID')
                ->count('Faculty_ID');
        })->toArray();

        $present = $todayAttendance;
        $absent = max($facultyCount - $present, 0);
        $facultyAttendanceRate = ['Present' => $present, 'Absent' => $absent];

        return view('admin.dashboard', compact(
            'facultyCount', 'todayAttendance', 'attendanceTrendLabels', 'attendanceTrendData', 'facultyAttendanceRate',
            'classesScheduledToday', 'lateArrivalsToday', 'recentFacultyActivity', 'upcomingSchedules'
        ));
    }


    public function addFaculty(){
        return view('admin.manageFaculty.addFaculty');
    }
    
    public function viewFaculty(){

        // Fetch faculty data from the database
        $faculty = \App\Models\Faculty::all(); // Assuming you have a Faculty model

        return view('admin.manageFaculty.viewFaculty', compact('faculty'));
    }

    public function addFacultyStore(Request $request) {
        // Validate the request data
        $request->validate([
            'rfid_tag' => 'required|string|max:255',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:faculty,Email',
            'Position' => 'required|string|max:255',
            'Gender' => 'required|string',
        ]);

        // Create a new faculty record
        Faculty::create([
            'rfid_tag' => $request->rfid_tag,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Email' => $request->Email,
            'Position' => $request->Position,
            'Gender' => $request->Gender,
        ]);

        // Redirect back with success message
        return redirect()->route('admin.manageFaculty.viewFaculty')->with('success', 'Faculty added successfully.');
    }

    // Functions to add Class Schedule, view Class Schedule and Other functionalities
    public function addClassSchedule() {
        $faculties = \App\Models\Faculty::all();
        return view('admin.manageClassSchedule.addClassSched', compact('faculties'));
    }
    public function viewClassSchedule()
    {
        $schedules = \App\Models\ClassSchedule::with('faculty')->get();

        // Group by Faculty full name, or 'Unassigned' if not linked properly
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            if ($schedule->faculty) {
                return $schedule->faculty->FirstName . ' ' . $schedule->faculty->LastName;
            }
            return 'Unassigned';
        });

        return view('admin.manageClassSchedule.viewClassSched', compact('groupedSchedules'));
    }
    public function storeClassSchedule()
    {
        request()->validate([
            'subject' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'Yearlvl' => 'required|string|max:255',
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculty,Faculty_ID',
        ]);

        \App\Models\ClassSchedule::create([
            'subject' => request('subject'),
            'section' => request('section'),
            'Yearlvl' => request('Yearlvl'),
            'day_of_week' => request('day_of_week'),
            'start_time' => request('start_time'),
            'end_time' => request('end_time'),
            'room' => request('room'),
            'faculty_ID' => request('faculty_id'),
        ]);

        return redirect()->route('admin.manageClassSchedule.addClassSched')->with('success', 'Class Schedule added successfully.');
    }
    public function dailyAttendance() {
        $today = \Carbon\Carbon::now('Asia/Manila')->toDateString(); // YYYY-MM-DD

        $attendances = \App\Models\Attendance::with('faculty', 'classSchedule')
            ->whereDate('date', $today)
            ->get();

        return view('admin.attendance.dailyAttendance', compact('attendances'));
    }
    public function count()
    {
        $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();

        $count = \App\Models\Attendance::whereDate('date', $today)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Return today's attendance rows as JSON and rendered HTML (for legacy clients).
     */
    public function dailyAttendanceRows(Request $request)
    {
        $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();

        $attendances = \App\Models\Attendance::with(['faculty', 'classSchedule'])
            ->whereDate('date', $today)
            ->latest()
            ->get();

        // structured rows for Vue
        $rows = $attendances->map(function ($a) {
            return [
                'id' => $a->id ?? null,
                'faculty_name' => $a->faculty ? ($a->faculty->FirstName . ' ' . $a->faculty->LastName) : null,
                'rfid_tag' => $a->rfid_tag,
                'subject' => $a->classSchedule?->subject ?? null,
                'date' => $a->date,
                'time_in' => $a->time_in,
                'time_out' => $a->time_out,
                'status' => $a->status,
            ];
        })->toArray();

        // rendered HTML for legacy polling
        $html = view('admin.attendance.partials.rows', compact('attendances'))->render();

        return response()->json([
            'rows' => $rows,
            'html' => $html,
            'count' => $attendances->count(),
        ]);
    }

    public function editFaculty($id) {
        // Find the faculty by ID
        $faculty = Faculty::findOrFail($id);
        
        // Return the edit view with the faculty data
        return view('admin.manageFaculty.editFaculty', compact('faculty'));
    }
    public function editFacultyPatch(Request $request, $id) {
        $request->validate([
            'rfid_tag' => 'required|string|max:255',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:faculty,Email,' . $id . ',Faculty_ID',
            'Position' => 'required|string|max:255',
            'Gender' => 'required|string',
        ]);

        $faculty = Faculty::findOrFail($id);
        $faculty->update($request->only(['rfid_tag', 'FirstName', 'LastName', 'Email', 'Position', 'Gender']));

        return redirect()->route('admin.manageFaculty.viewFaculty')->with('success', 'Faculty updated successfully.');
    }
    public function deleteFaculty($id) {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();

        return redirect()->route('admin.manageFaculty.viewFaculty')->with('success', 'Faculty deleted successfully.');
    }
    public function editClassSchedule($id) {
        // Find the class schedule by ID
        $schedule = \App\Models\ClassSchedule::findOrFail($id);
        $faculties = \App\Models\Faculty::all();

        return view('admin.manageClassSchedule.editClassSched', compact('schedule', 'faculties'));
    }
   
    public function updateClassSchedule(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        
        $request->validate([
            'faculty_ID' => 'required',
            'subject' => 'required|string|max:255',
            'Yearlvl' => 'required',
            'section' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string|max:255',
        ]);
        $schedule->update($request->all());

        return redirect()->route('admin.manageClassSchedule.viewClassSched')->with('success', 'Schedule updated successfully!');
    }

    public function deleteClassSchedule($id){
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.manageClassSchedule.viewClassSched')->with('success', 'Schedule deleted successfully.');
    }
    public function attendanceReport(Request $request)
    {
        $query = Attendance::with(['faculty', 'classSchedule']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $request->merge([
                'start_date' => Carbon::now('Asia/Manila')->format('Y-m-d'),
                'end_date' => Carbon::now('Asia/Manila')->format('Y-m-d')
            ]);
            $query->whereDate('date', Carbon::now('Asia/Manila'));
        }

        if ($request->filled('faculty_id')) {
            $query->where('Faculty_ID', $request->faculty_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        return view('admin.attendance.attendanceReport', [
            'data' => $data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    }

    public function downloadAttendanceReport(Request $request)
    {
        $query = Attendance::with(['faculty', 'classSchedule']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $request->merge([
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d')
            ]);
            $query->whereDate('date', now());
        }

        $attendances = $query->get();

        $pdf = Pdf::loadView('reports.attendance', [
            'attendances' => $attendances,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return $pdf->download('attendance_report.pdf');
    }
    public function downloadAttendanceCSV(Request $request)
    {
        $query = Attendance::with(['faculty', 'classSchedule']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->whereDate('date', now());
        }

        $attendances = $query->get();

        $filename = 'attendance_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($attendances) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Faculty Name', 'RFID', 'Date', 'Time In', 'Time Out', 'Status', 'Subject', 'Section', 'Room']);

            foreach ($attendances as $a) {
                $facultyName = $a->faculty ? ($a->faculty->FirstName . ' ' . $a->faculty->LastName) : '';
                $subject = $a->classSchedule?->subject ?? '';
                $section = $a->classSchedule?->section ?? '';
                $room = $a->classSchedule?->room ?? '';

                fputcsv($out, [
                    $facultyName,
                    $a->rfid_tag,
                    $a->date,
                    $a->time_in,
                    $a->time_out,
                    $a->status,
                    $subject,
                    $section,
                    $room,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}