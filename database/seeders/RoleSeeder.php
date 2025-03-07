<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$adminRole = new Role();
		$adminRole->name = RoleType::ADMIN;
		$adminRole->description = 'Admin is allowed to manage everything of the app.';
		$adminRole->guard_name = 'api';
		$adminRole->save();

		$salesmaneRole = new Role();
		$salesmaneRole->name = RoleType::STAFF;
		$salesmaneRole->description = 'Staff is allowed to manage everything related to Staff.';
		$adminRole->guard_name = 'api';
		$salesmaneRole->save();

		$salesmaneRole = new Role();
		$salesmaneRole->name = RoleType::TRAINER;
		$salesmaneRole->description = 'Trainer is allowed to manage everything related to Trainer.';
		$adminRole->guard_name = 'api';
		$salesmaneRole->save();

		$salesmaneRole = new Role();
		$salesmaneRole->name = RoleType::MEMBER;
		$salesmaneRole->description = 'Member is allowed to manage everything related to Member.';
		$adminRole->guard_name = 'api';
		$salesmaneRole->save();
	}
}
