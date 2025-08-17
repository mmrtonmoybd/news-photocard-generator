<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use function Symfony\Component\Clock\now;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the super_admin role exists
        $superAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'admin')->first();

        // Create a new user or update if exists
        $admin = Admin::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Unique identifier
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin'), // Replace with a strong password
                'email_verified_at' => now(),
            ]
        );

        // Assign the super_admin role to the user
        $admin->assignRole($superAdminRole);
    }
}
