<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Player;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class GameLobby extends Component
{
    public $games;
    public $selectedGame;

    public function mount()
    {
        $this->games = Game::where('status', 'waiting')->get();
    }

    public function joinGame($gameId)
    {
        $game = Game::findOrFail($gameId);
        $user = Auth::user()->load('wallet');

        if ($user->wallet->amount < $game->bet_amount) {
            Notification::make()
                ->title('Insufficient balance')
                ->body('You do not have enough balance to join this game. Minimum required balance: '. number_format($game->bet_amount).'coins')
                ->danger()
                ->send();
            return ;
        }

        $player = Player::firstOrCreate([
            'user_id' => Auth::id(),
            'game_id' => $game->id
        ]);

        session(['game_id' => $game->id]);
        return redirect()->route('game.table');
    }

    public function render()
    {
        return view('livewire.game-lobby', ['games' => $this->games]);
    }
}
