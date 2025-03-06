<?php

namespace Database\Seeders;

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
                'is_session_based' => false,
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
                'is_session_based' => true,
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
