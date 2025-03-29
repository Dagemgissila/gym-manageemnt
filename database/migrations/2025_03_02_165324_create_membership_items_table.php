<?php

use App\Enums\MembershipFor;
use App\Enums\MembershipItem;
use App\Enums\YesNo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('membership_items', function (Blueprint $table) {
            $table->id();
            $table->string('membership_name');
            $table->string('description')->nullable();
            $table->foreignId('membership_type_id')
                ->constrained('membership_types')
                ->onDelete('restrict');

            $table->integer('duration_days');
            $table->integer('upgradable_limit');
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('discount_available')->default(false);
            $table->boolean('installment_available')->default(false);
            $table->integer('free_freezes_allowed');
            $table->integer('freeze_duration_max_weeks');
            $table->enum('paid_freeze_allowed', YesNo::getValues())->default(YesNo::YES);
            $table->enum("membership_for", MembershipFor::getValues())->default(MembershipFor::INDIVIDUAL);
            $table->boolean('gym_access');

            //suspenssion
            $table->integer('suspend_based_on_balance');
            $table->integer('suspend_after');

            $table->integer('accessible_days');
            $table->integer('sessions');

            $table->boolean('link_access_to_booked_appts')->default(false);



            $table->boolean('status')->default(true);
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

    public function down(): void
    {
        Schema::dropIfExists('membership_items');
    }
}
