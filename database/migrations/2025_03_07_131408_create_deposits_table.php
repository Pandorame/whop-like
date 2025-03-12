<?php

use App\Helpers\Enums\DepositStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('to_admin_id')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->integer('payment_id');
            $table->integer('payment_account_id');
            $table->decimal('amount',15,2);
            $table->decimal('points',15,2)->default(0);
            $table->text('slip');
            $table->string('status')->default(DepositStatus::Processing);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
