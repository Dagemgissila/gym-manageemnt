<?php

namespace Database\Seeders;

use App\Classes\PermsSeed;
use App\Enums\RoleType;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve the admin role (assuming it exists)
        $adminRole = Role::where("name", RoleType::ADMIN)->first();

        // assign all permissions to admin role:
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);


        // Create the admin user
        $adminUser = User::create([
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'mobile_number' => '1234567890',
            'password' => Hash::make('password'), // Change to a secure password
            'profile_picture' => 'default.png',
            'status' => true, // Using your enum
            'gender' => 'Male', // or "Female"
            'date_of_birth' => now()->subYears(30)->format('Y-m-d'),
            'address' => '123 Admin Street',
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '0987654321', // removed extra space in key
            'hire_date' => now()->format('Y-m-d'),
            'salary' => 0.00,
            'assigned_location' => 'Head Office',
        ]);

        // Assign admin role to the created admin user
        $adminUser->assignRole($adminRole);
        User::factory(30)->create();
    }
}
