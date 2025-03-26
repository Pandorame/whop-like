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
        Schema::create('player_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('card_1', 10)->nullable();
            $table->string('card_2', 10)->nullable();
            $table->string('card_3', 10)->nullable();
            $table->integer('score')->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('win_amount', 10, 2)->default(0);
            $table->integer('odds')->default(1);
            $table->enum('status', ['pending', 'lost', 'won'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('player_rounds');
        Schema::enableForeignKeyConstraints();
    }
};
