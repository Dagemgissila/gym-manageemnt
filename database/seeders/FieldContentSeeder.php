<?php

namespace Database\Seeders;

use App\Models\FieldContent;
use Illuminate\Database\Seeder;

class FieldContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FieldContent::insert([
            [
                "name" => "Lead Source",
                "key" => "lead_source"
            ],
            [
                "name" => "Fitness Goal",
                "key" => "fitness_goals"
            ],
            [
                "name" => "Location",
                "key" => "location"
            ],
            [
                "name" => "Preferred Contact Method",
                "key" => "preferred_contact_method"
            ],

            [
                "name" => "Interested In",
                "key" => "interested_in"
            ],
            [
                "name" => "Preferred Workout Time",
                "key" => "preferred_workout_time"
            ],
        ]);
    }
}
