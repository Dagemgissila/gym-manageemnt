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
        Schema::create('base_currencies', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name");
            $table->string("symbol");
            $table->string("decimal_place");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_currencies');
    }
};
