<?php

namespace Database\Seeders;

use App\Classes\PermsSeed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermsSeed::seedPermissions();
    }
}
