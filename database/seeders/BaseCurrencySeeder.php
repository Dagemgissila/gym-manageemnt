<?php

namespace Database\Seeders;

use App\Models\BaseCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BaseCurrency::create([
            "code" => "USD",
            "name" => "US DOLLAR",
            "symbol" => "$",
            "decimal_place" => 2
        ]);
    }
}
