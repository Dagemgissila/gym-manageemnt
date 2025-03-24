<?php

use App\Enums\ContactMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prefered_contact_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")->nullable()->constrained("members")->onDelete('cascade');
            $table->enum("prefered_contact_method", ContactMethod::getValues())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefered_contact_methods');
    }
};
