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
            ["field_name" => "First Name", "field_key" => "first_name", "prospect" => "YES", "trial" => "YES", "member" => "NO"],
            ["field_name" => "Last Name", "field_key" => "last_name", "prospect" => "YES", "trial" => "YES", "member" => "NO"],
            ["field_name" => "Mobile Number", "field_key" => "mobile_number", "prospect" => "YES", "trial" => "YES", "member" => "NO"],
            ["field_name" => "Email", "field_key" => "email", "prospect" => "NO", "trial" => "NO", "member" => "YES"],
            ["field_name" => "Date of Birth", "field_key" => "date_of_birth", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Gender", "field_key" => "gender", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Blacklisted", "field_key" => "blacklisted", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Lead Source", "field_key" => "lead_source", "prospect" => "NO", "trial" => "NO", "member" => "NO"],
            ["field_name" => "Interested In", "field_key" => "interested_in", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Preferred Contact Method", "field_key" => "preferred_contact_method", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Notes", "field_key" => "notes", "prospect" => "NO", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Emergency Contact Name", "field_key" => "emergency_contact_name", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Emergency Contact Mobile", "field_key" => "emergency_contact_mobile", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Photo", "field_key" => "photo", "prospect" => "YES", "trial" => "YES", "member" => "YES"],
            ["field_name" => "Status", "field_key" => "status", "prospect" => "NO", "trial" => "NO", "member" => "NO"],
        ]);
    }

}
