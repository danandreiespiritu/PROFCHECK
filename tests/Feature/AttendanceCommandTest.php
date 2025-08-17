<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Faculty;
use App\Models\Attendance;

class AttendanceCommandTest extends TestCase
{
    public function test_mark_faculty_absents_command_runs()
    {
        // Ensure there is at least one faculty with schedule for today (seeder should do this)
        $this->artisan('app:mark-faculty-absents')->assertExitCode(0);

        $this->assertTrue(true); // basic smoke test; further assertions require DB setup
    }
}
