<?php

use App\Livewire\Deposit;
use App\Livewire\DepositHistory;
use App\Livewire\History;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
    Route::get('histories',History::class)->name('histoires');
    Route::get('deposit/histories',DepositHistory::class)->name('deposit.history');
});

require __DIR__.'/auth.php';
