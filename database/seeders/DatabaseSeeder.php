<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample faculty
        $this->call([\Database\Seeders\FacultySeeder::class]);

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ],[
            'name' => 'Admin',
            'password' => bcrypt('B8@Dnz7U?}{M'),
            'usertype' => 'admin'
        ]);

        // Create faculty user linked to first faculty
        $faculty = \App\Models\Faculty::first();
        if ($faculty) {
            $user = User::firstOrCreate([
                'email' => $faculty->Email
            ],[
                'name' => $faculty->FirstName . ' ' . $faculty->LastName,
                'password' => bcrypt('password'),
                'usertype' => 'faculty',
                'Faculty_ID' => $faculty->Faculty_ID,
            ]);
        }

        // Seed roles and assign
        $this->call([\Database\Seeders\UserRoleSeeder::class]);
    }
}
