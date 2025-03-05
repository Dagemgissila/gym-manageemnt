<?php

use App\Enums\ActiveInactive;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_rules', function (Blueprint $table) {
            $table->id();
            $table->string('setting_rule');
            $table->string('setting_value');
            $table->enum('status', ActiveInactive::getValues())->default(ActiveInactive::ACTIVE);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_rules');
    }
};
