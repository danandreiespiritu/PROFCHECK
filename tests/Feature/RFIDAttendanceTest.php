<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Faculty;

class RFIDAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rfid_attendance_endpoint_calls_command_and_returns_faculty()
    {
        // Create a faculty
        $faculty = Faculty::create([
            'rfid_tag' => 'RFID_TEST_01',
            'FirstName' => 'Test',
            'LastName' => 'Faculty',
            'Email' => 'test.faculty@example.com',
        ]);

        // Mock Artisan call
        Artisan::shouldReceive('call')
            ->once()
            ->with('app:mark-daily-attendance', ['rfid' => 'RFID_TEST_01'])
            ->andReturn(0);

        // Also mock the output() call used by the controller
        Artisan::shouldReceive('output')
            ->once()
            ->andReturn('Marked present');

        $response = $this->postJson('/rfid-attendance', ['rfid' => 'RFID_TEST_01'], ['Authorization' => 'Bearer testkey']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Test Faculty']);
    }
}
