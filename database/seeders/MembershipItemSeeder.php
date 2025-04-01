<?php

namespace Database\Seeders;

use App\Enums\MembershipFor;
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
                'upgradable_limit' => 1,
                'price' => 50.00,
                'discount_available' => true,
                'installment_available' => false,
                'free_freezes_allowed' => 2,
                'freeze_duration_max_weeks' => 2,
                "validity" => 30,
                'paid_freeze_allowed' => YesNo::YES,
                'membership_for' => MembershipFor::INDIVIDUAL,
                'gym_access' => true,
                'status' => 0,
                "suspend_based_on_balance" => 1,
                'suspend_after' => 1,
                'accessible_days' => 1,
                'sessions' => 1,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'link_access_to_booked_appts' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_name' => 'Premium Membership',
                'description' => 'Access to all gym facilities including spa and sauna.',
                'membership_type_id' => 2, // Replace with a valid membership type ID
                'duration_days' => 90,
                'upgradable_limit' => 1,
                'price' => 150.00,
                'discount_available' => false,
                'installment_available' => true,
                'free_freezes_allowed' => 3,
                'freeze_duration_max_weeks' => 4,
                "validity" => 30,
                'paid_freeze_allowed' => YesNo::NO,
                'membership_for' => MembershipFor::GROUP,
                'gym_access' => true,
                'status' => 1,
                "suspend_based_on_balance" => 1,
                'suspend_after' => 1,
                'accessible_days' => 1,
                'sessions' => 1,
                'created_by' => 1, // Replace with a valid user ID
                'updated_by' => 1, // Replace with a valid user ID
                'link_access_to_booked_appts' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
