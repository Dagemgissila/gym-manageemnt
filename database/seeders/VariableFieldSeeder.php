<?php

namespace Database\Seeders;

use App\Enums\ContactMethod;
use App\Enums\InterestedIn;
use App\Enums\LeadSource;
use App\Models\VariableFields;
use Illuminate\Database\Seeder;
use App\Models\FieldContent;
use Log;

class VariableFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing field content IDs
        $fieldContents = FieldContent::all()->keyBy('name'); // Use name as key


        // Ensure we have field content records before seeding
        if (empty($fieldContents)) {
            return;
        }
        Log::info($fieldContents['Lead Source']->id);

        // Insert sample data
        VariableFields::insert([
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::WEBSITE,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::WALK_IN,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::SOCIAL_MEDIA,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::REFERRAL,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::EVENT,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Lead Source']->id ?? null,
                "value" => LeadSource::OTHER,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Fitness Goal']->id ?? null,
                "value" => "Weigh Loss",
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Preferred Contact Method']->id ?? null,
                "value" => ContactMethod::PHONE,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Location']->id ?? null,
                "value" => 'Addis Ababa',
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],

            //contact method
            [
                "field_content_id" => $fieldContents['Preferred Contact Method']->id ?? null,
                "value" => ContactMethod::EMAIL,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Preferred Contact Method']->id ?? null,
                "value" => ContactMethod::WHATSUP,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Preferred Contact Method']->id ?? null,
                "value" => ContactMethod::SMS,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],

            //interested in

            [
                "field_content_id" => $fieldContents['Interested In']->id ?? null,
                "value" => InterestedIn::PERSONAL_TRAINING,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Interested In']->id ?? null,
                "value" => InterestedIn::GYM_ACCESS,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Interested In']->id ?? null,
                "value" => InterestedIn::GROUP_CLASSES,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Interested In']->id ?? null,
                "value" => InterestedIn::TRANSFORMATION_PACK,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "field_content_id" => $fieldContents['Interested In']->id ?? null,
                "value" => InterestedIn::OTHER,
                "status" => false,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
