<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Faculty;
use App\Models\Attendance;
use App\Models\ClassSchedule;

class MarkDailyAttendance extends Command
{
    protected $signature = 'app:mark-daily-attendance {rfid}';
    protected $description = 'Mark daily attendance for faculty by RFID tag';

    public function handle()
    {
        $rfidTag = $this->argument('rfid');
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $currentTime = $now->format('H:i');
        $currentDay = $now->format('l');

        $faculty = Faculty::where('rfid_tag', $rfidTag)->first();

        if (!$faculty) {
            $this->error("No faculty found with RFID: {$rfidTag}");
            return;
        }

        // Get all schedules for this faculty on the current day (e.g., every Monday)
        $classSchedules = ClassSchedule::where('faculty_ID', $faculty->Faculty_ID)
            ->where('day_of_week', $currentDay)
            ->get();

        $matched = false;

        foreach ($classSchedules as $schedule) {
            if ($schedule->start_time <= $currentTime && $schedule->end_time >= $currentTime) {
                $attendance = Attendance::where('faculty_ID', $faculty->Faculty_ID)
                    ->where('class_schedule_ID', $schedule->id)
                    ->where('date', $today)
                    ->first();

                if ($attendance) {
                    if ($attendance->time_out === null) {
                        $attendance->time_out = $now; // store Carbon instance
                        $attendance->status = 'Present';
                        $attendance->save();
                        $this->info("Time-out recorded for schedule ID: {$schedule->id}");
                    } else {
                        $this->info("Already timed out for schedule ID: {$schedule->id}");
                    }
                } else {
                    Attendance::create([
                        'Faculty_ID' => $faculty->Faculty_ID,
                        'class_schedule_ID' => $schedule->id,
                        'rfid_tag' => $rfidTag,
                        'date' => $today,
                        'time_in' => $now,
                        'time_out' => null,
                        'status' => 'Present',
                    ]);
                    $this->info("Time-in recorded for schedule ID: {$schedule->id}");
                }

                $matched = true;
                break;
            }
        }

        if (!$matched) {
            $this->error("No active class schedule at this time.");
        }
    }

}
