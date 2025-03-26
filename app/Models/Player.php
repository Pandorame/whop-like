<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function rounds()
    {
        return $this->belongsToMany(Round::class)->withPivot(['card_1', 'card_2', 'card_3', 'score']);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
