<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PlayerRound;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class GameHistory extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, wins, losses
    public $gameId = '';
    public $gameIdFilter = null;
    public $totalBetAmount = 0;
    public $totalWinAmount = 0;
    public $netProfit = 0;
    
    public function mount()
    {
        $this->calculateSummary();
    }
    
    public function render()
    {
        
        return view('livewire.game-history');
    }

    #[Computed()]
    public function history()
    {

        $query = $this->getFilteredQuery();
        
        return $query->latest()->paginate(10);
    }

    public function filterByGameId()
    {
        if (!empty($this->gameId)) {
            $this->gameIdFilter = $this->gameId;
            $this->resetPage();
            $this->calculateSummary();
        }
    }

    public function clearGameIdFilter()
    {
        $this->gameIdFilter = null;
        $this->gameId = '';
        $this->resetPage();
        $this->calculateSummary();
    }

    public function calculateSummary()
    {
        // Calculate totals based on the current filter
        $query = $this->getFilteredQuery();
        
        $this->totalBetAmount = $query->sum('amount');
        $this->totalWinAmount = $query->sum('win_amount');
        $this->netProfit = $this->totalWinAmount - $this->totalBetAmount;
    }
    
    /**
     * Get the filtered query based on current filters
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getFilteredQuery()
    {
        $user = Auth::user();
        
        $query = PlayerRound::query()
            ->whereHas('player', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            
        // Apply filters
        if ($this->filter === 'wins') {
            $query->winning();
        } elseif ($this->filter === 'losses') {
            $query->losing();
        }
        
        // Apply game ID filter if set
        if ($this->gameIdFilter) {
            $query->whereHas('round.game', function($q) {
                $q->where('id', $this->gameIdFilter);
            });
        }
        
        return $query;
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
        $this->calculateSummary();
    }
}
