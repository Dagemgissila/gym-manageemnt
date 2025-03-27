<?php

use App\Enums\MemberStatus;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string('country_code')->nullable();
            $table->string("mobile_number")->nullable();
            $table->string("email")->nullable()->unique();
            $table->string("location")->nullable();
            $table->enum("gender", ["Male", "Female"])->nullable();
            $table->date("date_of_birth")->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_mobile')->nullable();
            $table->boolean("medical_condition")->default(0);
            $table->text("medical_notes")->nullable();
            $table->text('photo')->nullable();
            $table->json('lead_sources')->nullable();
            $table->json('interested_in')->nullable();
            $table->json('fitness_goals')->nullable();
            $table->json('preferred_workout_time')->nullable();
            $table->json('preferred_contact_method')->nullable();

            $table->boolean("blacklisted")->default(0);
            $table->date("last_contacted_at")->nullable();
            $table->date("next_follow_up_at")->nullable();
            $table->date("converted_at")->nullable();
            $table->foreignId("last_membeship_item_id")->nullable()->references("id")->on('membership_items')->nullOnDelete();
            $table->text('biometric_data')->nullable();

            $table->enum("status", MemberStatus::getValues())->default(MemberStatus::PROSPECT);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
