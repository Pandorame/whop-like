<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Wallet extends Component
{
    public $balance;
    public $transactions = [];
    public $showTransactions = true;

    public function mount()
    {
        $user = User::where('id',Auth::id())->with('transitions','wallet')->first();
        $this->balance = $user->wallet->amount ?? 0;

        // Get recent transactions if needed
        $this->transactions = $user->transitions()->latest()->take(5)->get();

    }

    public function toggleTransactions()
    {
        $this->showTransactions = !$this->showTransactions;
    }

    public function render()
    {
        return view('livewire.wallet');
    }
}
