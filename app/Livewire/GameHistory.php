<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PlayerRound;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class GameHistory extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, wins, losses
    
    public function mount()
    {
        // Initialize component
    }
    
    public function render()
    {
        $user = Auth::user();
        
        $query = PlayerRound::with(['round.game', 'player.user'])
            ->whereHas('player', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            
        // Apply filters
        if ($this->filter === 'wins') {
            $query->winning();
        } elseif ($this->filter === 'losses') {
            $query->losing();
        }
        
        $history = $query->latest()->paginate(10);
        
        return view('livewire.game-history', [
            'history' => $history
        ]);
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
}
