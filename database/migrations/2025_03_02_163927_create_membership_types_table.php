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
        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();
            $table->string('membership_type');
            $table->string('membership_base');
            $table->boolean('live_membership');
            $table->string('background_color');
            $table->boolean('membership_overlap')->default(false);
            $table->boolean('status')->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->references('id')->on('users')
                ->nullOnDelete(); // Set to NULL on dele
            $table->foreignId('updated_by')
                ->nullable()
                ->references('id')->on('users')
                ->nullOnDelete(); // Set to NULL on dele

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
