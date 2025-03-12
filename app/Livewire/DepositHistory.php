<?php

namespace App\Livewire;

use App\Models\Deposit as DepositModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class DepositHistory extends Component
{
    use WithPagination;
    
    public $dateFilter = '';
    public $statusFilter = '';
    public $search = '';
    
    public $totalDeposits = 0;
    public $completedDeposits = 0;
    public $pendingDeposits = 0;
    
    protected $queryString = [
        'dateFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    public function updatedDateFilter()
    {
        $this->resetPage();
    }
    
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function viewSlip($slipPath)
    {
        // This will be used to show the slip in a modal
        $this->dispatch('open-modal', ['slip' => $slipPath]);
    }
    
    public function getDepositsProperty()
    {
        $query = DepositModel::query()
            ->where('user_id',Auth::id())
            ->with(['toAdmin','user', 'payment', 'paymentAccount'])
            ->orderBy('created_at', 'desc');
        
        // Apply date filter
        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                          ->whereYear('created_at', Carbon::now()->subMonth()->year);
                    break;
            }
        }
        
        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('amount', 'like', '%' . $this->search . '%')
                  ->orWhereHas('toAdmin', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        // Get statistics
        $this->calculateStatistics($query);
        
        return $query->paginate(9);
    }
    
    protected function calculateStatistics($query)
    {
        // Clone the query to avoid modifying the original
        $statsQuery = clone $query;
        
        // Calculate total deposits
        $this->totalDeposits = $statsQuery->sum('amount');
        
        // Calculate completed deposits
        $completedQuery = clone $query;
        $this->completedDeposits = $completedQuery->where('status', 'completed')->sum('amount');
        
        // Calculate pending deposits
        $pendingQuery = clone $query;
        $this->pendingDeposits = $pendingQuery->where('status', 'pending')->sum('amount');
    }
    
    public function render()
    {
        return view('livewire.deposit-history', [
            'deposits' => $this->deposits,
        ]);
    }
}
