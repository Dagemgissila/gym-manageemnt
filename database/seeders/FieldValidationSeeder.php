<?php

namespace Database\Seeders;

use App\Models\FieldValidation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FieldValidation::insert([
            // Personal Information
            [
                "field_name" => "First Name",
                "field_key" => "first_name",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "text",
            ],
            [
                "field_name" => "Last Name",
                "field_key" => "last_name",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "text",
            ],
            [
                "field_name" => "Gender",
                "field_key" => "gender",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "icon_select", // Icons will be provided by Ali
            ],
            [
                "field_name" => "Date of Birth",
                "field_key" => "date_of_birth",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "date",
            ],
            [
                "field_name" => "Profile Picture",
                "field_key" => "photo",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "file",
            ],
            [
                "field_name" => "Lead Source",
                "field_key" => "lead_source",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "dropdown",
            ],
            [
                "field_name" => "Interested In",
                "field_key" => "interested_in",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Personal Information",
                "input_type" => "dropdown",
            ],

            [
                "field_name" => "Mobile Number",
                "field_key" => "mobile_number",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Contact Information",
                "input_type" => "tel",
            ],
            [
                "field_name" => "Email Address",
                "field_key" => "email",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Contact Information",
                "input_type" => "email",
            ],
            [
                "field_name" => "Location",
                "field_key" => "location",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Contact Information",
                "input_type" => "dropdown",
            ],
            [
                "field_name" => "Preferred Contact Method",
                "field_key" => "preferred_contact_method",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Contact Information",
                "input_type" => "dropdown",
            ],

            // Fitness & Preferences
            [
                "field_name" => "Fitness Goals",
                "field_key" => "fitness_goals",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Fitness & Preferences",
                "input_type" => "dropdown",
            ],
            [
                "field_name" => "Preferred Workout Time",
                "field_key" => "preferred_workout_time",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Fitness & Preferences",
                "input_type" => "dropdown",
            ],
            [
                "field_name" => "Notes",
                "field_key" => "notes",
                "prospect" => "YES",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Fitness & Preferences",
                "input_type" => "textarea",
            ],

            // Medical Information
            [
                "field_name" => "Medical Condition",
                "field_key" => "medical_condition",
                "prospect" => "NO",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Medical Information",
                "input_type" => "toggle",
            ],
            [
                "field_name" => "Medical Notes",
                "field_key" => "medical_notes",
                "prospect" => "NO",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Medical Information",
                "input_type" => "textarea",
            ],
            [
                "field_name" => "Emergency Contact Name",
                "field_key" => "emergency_contact_name",
                "prospect" => "NO",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Medical Information",
                "input_type" => "text",
            ],
            [
                "field_name" => "Emergency Contact Mobile",
                "field_key" => "emergency_contact_mobile",
                "prospect" => "NO",
                "trial" => "YES",
                "membership" => "YES",
                "group" => "Medical Information",
                "input_type" => "tel",
            ],

            // Other
            [
                "field_name" => "Blacklisted",
                "field_key" => "blacklisted",
                "prospect" => "NO",
                "trial" => "NO",
                "membership" => "YES",
                "group" => "Other",
                "input_type" => "toggle",
            ],
        ]);
    }


}
