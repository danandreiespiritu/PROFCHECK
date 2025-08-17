<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Faculty;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\User;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_report_page_loads_and_csv_exports()
    {
        // Create an admin user and authenticate
        $admin = User::factory()->create([
            'usertype' => 'admin',
        ]);
        $this->actingAs($admin);

        // Create faculty, schedule and attendance
        $faculty = Faculty::create([
            'rfid_tag' => 'RFID_ADMIN_01',
            'FirstName' => 'Admin',
            'LastName' => 'Faculty',
            'Email' => 'admin.faculty@example.com',
        ]);

        $schedule = ClassSchedule::create([
            'faculty_ID' => $faculty->Faculty_ID,
            'subject' => 'Math 101',
            'section' => 'A',
            'Yearlvl' => '1st Year',
            'day_of_week' => now()->format('l'),
            'start_time' => now()->format('H:i'),
            'end_time' => now()->addHour()->format('H:i'),
            'room' => 'R101',
        ]);

        $attendance = Attendance::create([
            'Faculty_ID' => $faculty->Faculty_ID,
            'class_schedule_ID' => $schedule->id,
            'rfid_tag' => $faculty->rfid_tag,
            'date' => now()->toDateString(),
            'time_in' => now(),
            'time_out' => null,
            'status' => 'Present',
        ]);

        // Call report page
        $response = $this->get('/admin/attendance/attendanceReport');
        $response->assertStatus(200);

        // Call CSV export
        $csvResponse = $this->get('/attendance-report/csv');
        $csvResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $csvResponse->headers->get('content-type'));
    }
}
