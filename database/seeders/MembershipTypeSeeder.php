<?php

namespace Database\Seeders;

use App\Enums\MembershipBase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('membership_types')->insert([
            [
                'membership_type' => 'Basic',
                'membership_base' => MembershipBase::SESSION_BASED,
                'live_membership' => true,
                'background_color' => '#FF5733',
                'membership_overlap' => false,
                'status' => false,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Premium',
                'membership_base' => MembershipBase::CLASSES_BASED,
                'live_membership' => true,
                'background_color' => '#33FF57',
                'membership_overlap' => true,
                'status' => true,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
