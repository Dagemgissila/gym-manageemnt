<?php

namespace Database\Seeders;

use App\Models\FieldContent;
use Illuminate\Database\Seeder;

class FieldContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FieldContent::insert([
            [
                "name" => "Source",
            ],
            [
                "name" => "Fitness Goal",
            ],
            [
                "name" => "Location",
            ],
        ]);
    }
}
