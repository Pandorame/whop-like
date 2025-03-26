<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerRound extends Model
{
    protected $fillable = [
        'player_id', 'round_id', 'card_1', 'card_2', 'score', 
        'amount', 'win_amount', 'status'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }
    
    // Get the game through the round relationship
    public function game()
    {
        return $this->hasOneThrough(Game::class, Round::class, 'id', 'id', 'round_id', 'game_id');
    }
    
    // Scope to get winning rounds
    public function scopeWinning($query)
    {
        return $query->where('status', 'won');
    }
    
    // Scope to get losing rounds
    public function scopeLosing($query)
    {
        return $query->where('status', 'lost');
    }
    
    // Get formatted cards
    public function getCardsAttribute()
    {
        return [$this->card_1, $this->card_2];
    }
    
    // Check if this round was a win
    public function isWin()
    {
        return $this->status === 'won';
    }
}
