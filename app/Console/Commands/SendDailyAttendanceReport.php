<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Mail\DailyAttendanceReport;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SendDailyAttendanceReport extends Command
{
    protected $signature = 'attendance:send-daily-report';
    protected $description = 'Send daily attendance report to the dean';

    public function handle()
    {
        $date = Carbon::now('Asia/Manila')->format('Y-m-d');

        $attendances = Attendance::with(['faculty', 'classSchedule'])
            ->whereDate('date', $date)
            ->get();

        if ($attendances->isEmpty()) {
            $this->info("No attendance data found for {$date}.");
            return;
        }

        $pdf = Pdf::loadView('reports.attendance', [
            'attendances' => $attendances,
            'start_date' => $date,
            'end_date' => $date,
        ])->output();

        Mail::to('danandreiespiritu@gmail.com')->send(new DailyAttendanceReport($pdf, $date, $date));

        $this->info("Attendance report sent for {$date}.");
    }
}
