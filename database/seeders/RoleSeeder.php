<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Enums\RoleType;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
	public function run(): void
	{
		$adminRole = Role::create([
			'name' => RoleType::ADMIN,
			'description' => 'Admin is allowed to manage everything of the app.',
			'is_trainer' => 0,
			'guard_name' => 'api',
		]);

		$staffRole = Role::create([
			'name' => RoleType::STAFF,
			'description' => 'Staff is allowed to manage everything related to Staff.',
			'is_trainer' => 0,
			'guard_name' => 'api',
		]);

		$trainerRole = Role::create([
			'name' => RoleType::TRAINER,
			'description' => 'Trainer is allowed to manage everything related to Trainer.',
			'is_trainer' => 1,
			'guard_name' => 'api',
		]);

		$memberRole = Role::create([
			'name' => RoleType::MEMBER,
			'description' => 'Member is allowed to manage everything related to Member.',
			'is_trainer' => 0,
			'guard_name' => 'api',
		]);
	}
}

