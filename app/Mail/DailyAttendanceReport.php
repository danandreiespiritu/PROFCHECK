<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAttendanceReport extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $start_date;
    public $end_date;

    public function __construct($pdfContent, $start_date, $end_date)
    {
        $this->pdfContent = $pdfContent;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function build()
    {
        return $this->subject('Daily Faculty Attendance Report')
            ->markdown('emails.daily_attendance_report')
            ->attachData($this->pdfContent, 'attendance_report.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
