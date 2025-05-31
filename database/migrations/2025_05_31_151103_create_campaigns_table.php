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
       // database/migrations/2025_05_31_000000_create_content_rewards_tables.php

Schema::create('campaigns', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->decimal('budget', 12, 2);
    $table->decimal('amount_paid', 12, 2)->default(0);
    $table->string('payout_structure'); // e.g. "$1,000/1%"
    $table->string('content_type'); // UGC, etc.
    $table->json('platforms')->nullable(); // Allowed platforms
    $table->boolean('is_active')->default(true);
    $table->foreignId('advertiser_id')->constrained('users');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
