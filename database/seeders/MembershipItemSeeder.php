<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\MembershipItem;
use App\Enums\YesNo;

class MembershipItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('membership_items')->insert([
            [
                'membership_name' => 'Standard Membership',
                'description' => 'Basic access to the gym with no additional perks.',
                'membership_type_id' => 1, // Replace with a valid membership type ID
                'duration_days' => 30,
                'upgradable' => true,
                'price' => 50.00,
                'discount_available' => true,
                'installment_available' => false,
                'free_freezes_allowed' => 2,
                'freeze_duration_max_weeks' => 2,
                'paid_freeze_allowed' => YesNo::YES,
                'gym_access' => true,
                'status' => MembershipItem::ACTIVE,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_name' => 'Premium Membership',
                'description' => 'Access to all gym facilities including spa and sauna.',
                'membership_type_id' => 2, // Replace with a valid membership type ID
                'duration_days' => 90,
                'upgradable' => false,
                'price' => 150.00,
                'discount_available' => false,
                'installment_available' => true,
                'free_freezes_allowed' => 3,
                'freeze_duration_max_weeks' => 4,
                'paid_freeze_allowed' => YesNo::NO,
                'gym_access' => true,
                'status' => MembershipItem::ACTIVE,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
