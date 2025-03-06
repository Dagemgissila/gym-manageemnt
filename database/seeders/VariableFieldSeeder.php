<?php

namespace Database\Seeders;

use App\Models\VariableFields;
use Illuminate\Database\Seeder;
use App\Models\FieldContent;

class VariableFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing field content IDs
        $fieldContentIds = FieldContent::pluck('id')->toArray();

        // Ensure we have field content records before seeding
        if (empty($fieldContentIds)) {
            return;
        }

        // Insert sample data
        VariableFields::insert([
            [
                "field_content_id" => $fieldContentIds[0] ?? null,
                "value" => "Sample Value 1",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContentIds[1] ?? null,
                "value" => "Sample Value 2",
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
