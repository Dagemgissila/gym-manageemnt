<?php

use App\Enums\YesNo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('field_validations', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_key');
            $table->enum('prospect', YesNo::getValues())->default(YesNo::NO);
            $table->enum('trial', YesNo::getValues())->default(YesNo::NO);
            $table->enum('membership', YesNo::getValues())->default(YesNo::NO);
            $table->string('group')->nullable(); // New column for grouping
            $table->string('input_type')->nullable(); // New column for input type
            $table->boolean("is_multiple")->default(0);
            $table->foreignId('field_content_id')->nullable()->constrained('field_contents')->onDelete('set null');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_validations');
    }
};
