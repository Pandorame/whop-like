<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Bet;
use App\Models\Round;
use Illuminate\Support\Facades\Auth;

class BettingPanel extends Component
{
    public $round;
    public $betAmount;

    public function mount()
    {
        $this->round = Round::where('status', 'ongoing')->latest()->first();
    }

    public function placeBet()
    {
        Bet::create([
            'player_id' => Auth::id(),
            'round_id' => $this->round->id,
            'amount' => $this->betAmount,
            'status' => 'pending'
        ]);

        $this->betAmount = '';
    }

    public function render()
    {
        return view('livewire.betting-panel');
    }
}
