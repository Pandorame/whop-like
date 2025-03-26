<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withPivot(['card_1', 'card_2', 'card_3', 'score']);;
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
