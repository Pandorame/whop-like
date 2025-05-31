<?php

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
        // New migration file
Schema::table('campaigns', function (Blueprint $table) {
    $table->string('payout_structure_type')->default('per_view'); // per_view, percentage, fixed
    $table->decimal('payout_amount', 10, 2)->nullable(); // Amount per view/percentage
    $table->integer('payout_threshold')->nullable(); // Views required
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
