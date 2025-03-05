<?php

namespace Database\Factories;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'mobile_number' => $this->faker->phoneNumber,
            'password' => Hash::make('password'), // Default password
            'profile_picture' => null,
            'status' => UserStatus::ACTIVE,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $this->faker->date('Y-m-d', '-30 years'),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_phone' => $this->faker->phoneNumber,
            'hire_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(2, 1000, 5000), // Random salary
            'assigned_location' => $this->faker->company,
        ];
    }

    /**
     * Assign a role to the user after creating.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Assign a random role if available
            $role = Role::inRandomOrder()->first();

            if ($role) {
                $user->assignRole($role);
            }
        });
    }
}
