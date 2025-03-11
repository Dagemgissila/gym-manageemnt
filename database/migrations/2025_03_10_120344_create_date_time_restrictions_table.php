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
        Schema::create('date_time_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_item_id')->references('id')->on('membership_items')->onDelete('cascade');
            $table->string('day');
            $table->time('from_time');
            $table->time('to_time');
            $table->enum('time_period', ['Range 1', 'Range 2']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_time_restrictions');
    }
};
