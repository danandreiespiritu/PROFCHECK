<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\Faculty;
use Carbon\Carbon;

class MarkFacultyAbsents extends Command
{
    protected $signature = 'app:mark-faculty-absents';
    protected $description = 'Automatically mark faculty absent if they have scheduled classes and no attendance logged.';

    public function handle()
    {
        $today = Carbon::now()->format('l'); // e.g. Monday
        $dateToday = Carbon::now()->toDateString();

        // Get all faculties who have classes today
        $faculties = Faculty::whereHas('classSchedules', function ($query) use ($today) {
            $query->where('day_of_week', $today);
        })->with(['classSchedules' => function ($query) use ($today) {
            $query->where('day_of_week', $today);
        }])->get();

        foreach ($faculties as $faculty) {
            foreach ($faculty->classSchedules as $schedule) {
                // Check if attendance for this class schedule exists
                $hasAttendance = Attendance::where('Faculty_ID', $faculty->Faculty_ID)
                    ->where('class_schedule_ID', $schedule->id) // Use $schedule->id
                    ->where('date', $dateToday)
                    ->exists();

                if (!$hasAttendance) {
                    Attendance::create([
                        'Faculty_ID' => $faculty->Faculty_ID,
                        'class_schedule_ID' => $schedule->id, // Use $schedule->id
                        'rfid_tag' => $faculty->rfid_tag,
                        'date' => $dateToday,
                        'time_in' => null,
                        'time_out' => null,
                        'status' => 'Absent',
                    ]);

                    $this->info("Marked absent: {$faculty->FirstName} {$faculty->LastName} for schedule ID: {$schedule->id}");
                } else {
                    $this->info("Already has attendance: {$faculty->FirstName} {$faculty->LastName} for schedule ID: {$schedule->id}");
                }
            }
        }
    }
}
