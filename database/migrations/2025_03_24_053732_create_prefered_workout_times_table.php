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
        Schema::create('prefered_workout_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained("members")->onDelete('cascade');
            $table->date("prefered_workout_time")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefered_workout_times');
    }
};
