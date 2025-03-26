<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class WithdrawHistory extends Component
{
    use WithPagination;
    
    public $dateFilter = '';
    public $statusFilter = '';
    public $search = '';
    
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
    
    #[Computed]
    public function withdrawals()
    {
        $user = Auth::user();
        
        $query = Withdraw::where('user_id', $user->id)
            // ->where('type', 'cash_out')
            ->orderBy('created_at', 'desc');
        
        // Apply date filter
        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', now()->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
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
                  ->orWhere('account_number', 'like', '%' . $this->search . '%')
                  ->orWhere('account_name', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.withdraw-history', [
            'withdrawals' => $this->withdrawals,
        ]);
    }
}
