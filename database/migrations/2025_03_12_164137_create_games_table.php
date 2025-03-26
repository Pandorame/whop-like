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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('max_players')->default(6); // Maximum number of players allowed
            $table->string('room_code')->unique(); // Unique game room ID
            $table->string('stream_id')->unique()->nullable(); // Unique game room ID
            $table->integer('bet_amount'); // Minimum bet per round
            $table->enum('status', ['waiting', 'in_progress', 'completed'])->default('waiting'); // Game state
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('games');
        Schema::enableForeignKeyConstraints();
    }
};
