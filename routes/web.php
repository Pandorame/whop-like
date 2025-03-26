<?php

use App\Livewire\Deposit;
use App\Livewire\DepositHistory;
use App\Livewire\History;
use App\Livewire\Withdraw;
use App\Livewire\WithdrawHistory;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Livewire\GameLobby;
use App\Livewire\GameTable;
use App\Livewire\BettingPanel;
use App\Livewire\GameHistory;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('deposit',Deposit::class)->name('deposit');
    Route::get('withdraw',Withdraw::class)->name('withdraw');
    Route::get('histories',History::class)->name('histoires');
    Route::get('deposit/histories',DepositHistory::class)->name('deposit.history');
    Route::get('withdraw/histories',WithdrawHistory::class)->name('withdraw.history');

    Route::get('/game/histories', GameHistory::class)->name('game.history');
    Route::get('/game', GameLobby::class)->name('game.lobby');
    Route::get('/game/table', GameTable::class)->name('game.table');
    Route::get('/game/betting', BettingPanel::class)->name('game.betting');
    Route::get('/all/transactions', \App\Livewire\AllTransactions::class)->name('all.transactions');
});

require __DIR__.'/auth.php';
