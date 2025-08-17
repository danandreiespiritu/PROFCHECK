<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (class_exists(Role::class)) {
            Role::firstOrCreate(['name' => 'admin']);
            Role::firstOrCreate(['name' => 'faculty']);
        }

        // Assign admin role to admin user
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin && method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        // Assign faculty role to faculty users
        $facultyUsers = User::where('usertype', 'faculty')->get();
        foreach ($facultyUsers as $u) {
            if (method_exists($u, 'assignRole')) {
                $u->assignRole('faculty');
            }
        }
    }
}
