<?php

use App\Helpers\Enums\WithdrawStatus;
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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('admin_id')->nullable()->constrained('admins','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('to_admin_id')->nullable()->constrained('admins','id')->cascadeOnUpdate()->cascadeOnUpdate();

            $table->decimal('amount');
            $table->decimal('transaction_fees')->default(0);
            $table->decimal('real_amount');

            $table->string('payment_id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('slip')->nullable();
            $table->string('status')->default(WithdrawStatus::Processing);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
