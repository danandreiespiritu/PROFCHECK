<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Faculty;

class AttendanceController extends Controller
{
    public function markDailyAttendance(Request $request)
    {
        // === Authentication Check ===
        $authHeader = $request->header('Authorization');
        $expectedKey = 'Bearer ' . env('RFID_AUTH_KEY'); // from .env

        if ($authHeader !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Log request
        \Log::info('RFID Attendance Request:', $request->all());

        // Get RFID from request
        $rfidInput = $request->input('rfid');
        $rfid = is_array($rfidInput) ? trim($rfidInput[0]) : trim($rfidInput);

        if (!$rfid) {
            return response()->json(['error' => 'RFID tag is required'], 400);
        }

        // Find faculty by RFID
        $faculty = Faculty::where('rfid_tag', $rfid)->first();
        if (!$faculty) {
            return response()->json(['message' => 'Faculty not found.'], 404);
        }

        // Call artisan command to mark attendance
        Artisan::call('app:mark-daily-attendance', ['rfid' => $rfid]);
        $output = Artisan::output();

        return response()->json([
            'message' => trim($output),
            'name' => $faculty->FirstName . ' ' . $faculty->LastName,
        ], 200);
    }
}
