<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        Faculty::create([
            'rfid_tag' => 'RFID10001',
            'FirstName' => 'Alice',
            'LastName' => 'Garcia',
            'Email' => 'alice.garcia@example.com',
            'Position' => 'Professor',
            'Gender' => 'Female',
        ]);

        Faculty::create([
            'rfid_tag' => 'RFID10002',
            'FirstName' => 'Brian',
            'LastName' => 'Lopez',
            'Email' => 'brian.lopez@example.com',
            'Position' => 'Lecturer',
            'Gender' => 'Male',
        ]);

        Faculty::create([
            'rfid_tag' => 'RFID10003',
            'FirstName' => 'Carmen',
            'LastName' => 'Santos',
            'Email' => 'carmen.santos@example.com',
            'Position' => 'Instructor',
            'Gender' => 'Female',
        ]);
    }
}
