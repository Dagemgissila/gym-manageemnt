<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("base_currency_id")->references("id")->on("base_currencies")->onDelete("cascade");
            $table->foreignId("foreign_currency_id")->references("id")->on("foreign_currencies")->onDelete("cascade");
            $table->decimal("exchange_rate");
            $table->boolean("is_exchnage_currency_reverse")->default(0);
            $table->date("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
