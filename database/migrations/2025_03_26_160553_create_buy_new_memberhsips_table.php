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
        Schema::create('buy_new_memberhsips', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")->constrained("members")->onDelete("cascade");
            $table->foreignId("membership_item_id")->references("id")->on("membership_items")->onDelete("cascade");
            $table->date("valid_from_date");
            $table->date("valid_to_date");
            $table->foreignId("trainner_id")->references("id")->on("users")->onDelete("cascade");
            $table->string("discount_type")->comment("percent or amount")->nullable();
            $table->string("discount_value")->nullable();
            $table->string("discount_reason")->nullable();
            $table->foreignId("voucher_id")->nullable()->references("id")->on('vouchers')->nullOnDelete();
            $table->date("purchased_date");
            $table->decimal("base_cost");
            $table->decimal("net_cost");
            $table->foreignId("created_by")->nullable()->references("id")->on("users")->nullOnDelete();
            $table->boolean("status")->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_new_memberhsips');
    }
};
