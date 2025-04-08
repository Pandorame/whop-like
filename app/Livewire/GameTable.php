<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Round;
use App\Models\PlayerRound;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\CountdownUpdated;
use App\Events\NewRoundStarted;
use App\Models\Bet;
use Illuminate\Support\Facades\Log;

class GameTable extends Component
{
    public $game;
    public $round;
    public $players;
    public $playerCards = [];
    public $cardsDealt = false;
    public $showAllCards = false;
    public $countdown = 60;
    public $betAmount = null;
    public $minBet = 1000;
    public $hasPlacedBet = false;
    public $isAutoBet = false;

    // In the mount method, add auto-start countdown logic
    public function mount()
    {
        if (session('game_id')) {
            $this->game = Game::find(session('game_id'));
            $this->round = Round::where('game_id', $this->game->id)
                ->where('status', 'ongoing')
                ->first();

            if (!$this->round) {
                $this->round = Round::create([
                    'game_id' => $this->game->id,
                    'status' => 'ongoing'
                ]);
            }

            $this->isHost = Auth::user()->is_host;

            $this->players = Player::where('game_id', $this->game->id)->get();
            $this->minBet = $this->game->min_bet ?? 1000;

            // broadcast(new CountdownUpdated($this->game->id, $this->countdown))->toOthers();

            // Check if cards are already dealt
            $existingCards = PlayerRound::where('round_id', $this->round->id)->count();
            if ($existingCards > 0) {
                $this->cardsDealt = true;
                $this->loadPlayerCards();

                // Check if current user has already bet
                $currentPlayer = $this->players->where('user_id', Auth::id())->first();
                if ($currentPlayer) {
                    $playerRound = PlayerRound::where('round_id', $this->round->id)
                        ->where('player_id', $currentPlayer->id)
                        ->first();

                    if ($playerRound && $playerRound->amount > 0) {
                        $this->hasPlacedBet = true;
                    }
                }
            } else {
                // Auto-start countdown if there are at least 2 players
                // if ($this->players->count() >= 2 && !$this->cardsDealt) {
                //     $this->dealCards();
                // }
            }
        }
    }

    public function loadPlayerCards()
    {
        $this->playerCards = PlayerRound::with(['player.user', 'round'])
            ->where('round_id', $this->round->id)
            ->get();
    }

    // Modify the generateCards method to return only 2 cards
    public function generateCards()
    {
        $suits = ['H', 'D', 'C', 'S']; // Hearts, Diamonds, Clubs, Spades
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        $deck = [];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $deck[] = $value . $suit;
            }
        }

        shuffle($deck);
        return array_slice($deck, 0, 2); // Return only 2 cards
    }

    // Modify the assignCards method to handle only 2 cards per player
    public function assignCards()
    {
        if (!$this->isHost) {
            return;
        }

        $cards = explode(',', $this->cardInput);
        if (count($cards) < 2) {
            session()->flash('error', 'Please enter at least 2 cards');
            return;
        }

        // Clean up the input
        $cards = array_map('trim', $cards);

        // Validate card format
        foreach ($cards as $card) {
            if (!preg_match('/^([2-9]|10|[JQKA])[HDCS]$/', $card)) {
                session()->flash('error', 'Invalid card format. Use format like 10H, JD');
                return;
            }
        }

        // Assign cards to players
        $playerIndex = 0;
        foreach ($this->players as $player) {
            if ($playerIndex * 2 + 1 < count($cards)) {
                $playerCards = [
                    $cards[$playerIndex * 2],
                    $cards[$playerIndex * 2 + 1]
                ];

                $playerRound = PlayerRound::where('round_id', $this->round->id)
                    ->where('player_id', $player->id)
                    ->first();

                if ($playerRound) {
                    $playerRound->update([
                        'card_1' => $playerCards[0],
                        'card_2' => $playerCards[1],
                        'card_3' => null,
                        'score' => $this->calculateScore($playerCards)
                    ]);
                } else {
                    PlayerRound::create([
                        'round_id' => $this->round->id,
                        'player_id' => $player->id,
                        'card_1' => $playerCards[0],
                        'card_2' => $playerCards[1],
                        'card_3' => null,
                        'score' => $this->calculateScore($playerCards)
                    ]);
                }

                $playerIndex++;
            }
        }

        $this->cardsDealt = true;
        $this->loadPlayerCards();
        $this->cardInput = '';

        session()->flash('success', 'Cards assigned successfully');
    }

    public function calculateScore($cards)
    {
        $values = [];
        foreach ($cards as $card) {
            $value = substr($card, 0, -1); // Remove the suit

            if (in_array($value, ['J', 'Q', 'K'])) {
                $values[] = 10;
            } elseif ($value === 'A') {
                $values[] = 1;
            } else {
                $values[] = intval($value);
            }
        }

        $sum = array_sum($values);
        return $sum % 10; // Return the last digit
    }

    public function setBetAmount($amount)
    {
        $this->betAmount = $amount;
    }

    public function placeBet()
    {
        $this->validate([
            'betAmount' => 'required|numeric|min:' . $this->minBet,
        ]);

        $user = User::where('id', Auth::id())->with('wallet')->first();
        // Check if user has enough balance
        if ($user->wallet->amount < $this->betAmount) {
            if($this->isAutoBet){
                $this->betAmount = $user->wallet->amount;
            }else{
                session()->flash('error', 'Insufficient balance in your wallet');
                return;
            }
        }

        $currentPlayer = $this->players->where('user_id', Auth::id())->first();

        if (!$currentPlayer) {
            session()->flash('error', 'User Not found');
            return;
        }

        $playerRound = PlayerRound::where('round_id', $this->round->id)
            ->where('player_id', $currentPlayer->id)
            ->first();

        if (!$playerRound) {
            $playerRound = PlayerRound::create([
                'round_id' => $this->round->id,
                'player_id' => $currentPlayer->id,
            ]);
        }

         // Check if the player has already placed a bet
         if ($playerRound->bet_amount > 0) {
            session()->flash('error', 'You have already placed a bet for this round');
            return;
        }
        // Place bet
        $playerRound->update([
            'amount' => $this->betAmount
        ]);
        // Deduct from user's wallet
        $user->wallet->decrement('amount', $this->betAmount);

        $this->hasPlacedBet = true;
        $this->loadPlayerCards();

        session()->flash('message', ' Bet placed successfully');
    }

    public function autoBet()
    {
        $player = Player::where('game_id', $this->game->id)
            ->where('user_id', Auth::id())
            ->first();
        $roundPlayer = PlayerRound::where('round_id',$this->round->id)->where('player_id', $player->id)->first();

        if ((!$this->hasPlacedBet && !$roundPlayer) || $roundPlayer?->amount == '0.00') {
            $this->isAutoBet = true;
            $this->betAmount = $this->minBet;
            $this->placeBet();
        }
    }

    public function showResults()
    {
        $this->showAllCards = true;
    }

    public function leaveGame()
    {
        // Remove player from the game
        if (Auth::check()) {
            // Player::where('game_id', $this->game->id)
            //     ->where('user_id', Auth::id())
            //     ->delete();
        }

        $player =  Player::where('game_id', $this->game->id)
            ->where('user_id', Auth::id())
            ->first();

        $player->status = 'left';
        $player->game_id = null;
        $player->update();

        session()->forget('game_id');

        return redirect()->route('game.lobby');
    }

    public function render()
    {
        if ($this->isHost && empty($this->availableCards)) {
            $this->initializeCardDeck();
        }

        return view('livewire.game-table', [
            'players' => $this->players,
            'playerCards' => $this->playerCards,
            'isHost' => $this->isHost,
            'availableCards' => $this->availableCards,
            'selectedPlayer' => $this->selectedPlayer,
            'selectedCards' => $this->selectedCards
        ]);
    }

    // Add these properties to the GameTable class
    public $liveStreamId = null;
    public $isHost = false;
    public $cardInput = '';

    public function callForBets()
    {
        if (!$this->isHost) {
            return;
        }

        // Set countdown to 60 seconds
        $this->countdown = 5;

        // Broadcast event to start countdown on all clients
        broadcast(new CountdownUpdated($this->game->id, $this->countdown))->toOthers();

        // Start countdown on host's client too
        // $this->dispatch('startCountdown', 10);

        session()->flash('message', 'Called for bets. Players have 10 seconds to place their bets.');
    }


    public function determineWinner()
    {
        if (!$this->isHost) {
            return;
        }

        $this->showAllCards = true;

        // Auto-bet for players who haven't bet yet
        foreach ($this->players as $player) {
            $playerRound = PlayerRound::where('round_id', $this->round->id)
                ->where('player_id', $player->id)
                ->first();

            if ($playerRound && !$playerRound->amount) {
                // Auto bet minimum amount
                if ($playerRound->player->user->wallet->amount < $this->betAmount) {
                    $this->betAmount = $playerRound->player->user->wallet->amount;
                }else{
                    $this->betAmount = $this->minBet;
                }

                $playerRound->update([
                    'amount' => $this->betAmount
                ]);

                // Deduct from user's wallet
                $player->user->wallet->decrement('amount', $this->minBet);
            }
        }

        // Reload player cards after auto-betting
        $this->loadPlayerCards();

        // Find the host player
        $hostPlayer = Player::where('game_id',$this->game->id)->whereHas('user', function ($query) {
           return $query->where('is_host', true);
        })->first();
        
        if (!$hostPlayer) {
            session()->flash('error', 'Host player not found');
            return;
        }
        
        // Get host's cards and score
        $hostPlayerRound = collect($this->playerCards)->where('player_id', $hostPlayer->id)->first();
        
        if (!$hostPlayerRound) {
            session()->flash('error', 'Host has no cards for this round');
            return;
        }
        
        $hostScore = $hostPlayerRound->score;
        
        // Find players with scores higher than the host
        $winners = collect($this->playerCards)->filter(function($playerCard) use ($hostScore, $hostPlayer) {
            // Exclude the host from winners
            return $playerCard->score > $hostScore && $playerCard->player_id != $hostPlayer->id;
        });

        // Calculate total pot
        // $totalBet = collect($this->playerCards)->sum('amount');
        
        if ($winners->count() > 0) {
            // Distribute winnings among winners
            // $winAmount = $totalBet / $winners->count();

            foreach ($winners as $winner) {
                $user = $winner->player->user;
                
                // Check if the player has two cards with the same value (pair)
                $card1Value = substr($winner->card_1, 0, -1); // Remove suit
                $card2Value = substr($winner->card_2, 0, -1); // Remove suit
                
                // Convert face cards to their numeric value for comparison
                if (in_array($card1Value, ['J', 'Q', 'K'])) {
                    $card1Value = '10';
                }
                if (in_array($card2Value, ['J', 'Q', 'K'])) {
                    $card2Value = '10';
                }
                
                $hasPair = $card1Value === $card2Value;
                
                // For a pair: total payout is 3x the bet (original bet + 2x winnings)
                // For normal win: total payout is 2x the bet (original bet + 1x winnings)
                $winMultiplier = $hasPair ? 3 : 2;
                $winAmount = $winner->amount * $winMultiplier;
                
                // Add the winnings to the user's wallet
                $user->wallet->increment('amount', $winAmount);

                // Record the win in the player round
                $winner->update([
                    'status' => 'won',
                    'win_amount' => $winAmount
                ]);
            }
            
            // Mark all other players (except host) as losers
            foreach ($this->playerCards as $playerCard) {
                if (!$winners->contains('id', $playerCard->id)) { //$playerCard->player_id != $hostPlayer->id && 
                    $playerCard->update([
                        'status' => 'lost',
                    ]);
                }
            }

            
            session()->flash('message', 'Winners determined and winnings distributed.');
        } else {
            // If no winners, host gets the pot
            $hostUser = $hostPlayer->user;
            // $hostUser->wallet->increment('amount', $hostUser->amount);

             // Check if the player has two cards with the same value (pair)
             $card1Value = substr($hostUser->card_1, 0, -1); // Remove suit
             $card2Value = substr($hostUser->card_2, 0, -1); // Remove suit
             
             // Convert face cards to their numeric value for comparison
             if (in_array($card1Value, ['J', 'Q', 'K'])) {
                 $card1Value = '10';
             }
             if (in_array($card2Value, ['J', 'Q', 'K'])) {
                 $card2Value = '10';
             }
             
            $hasPair = $card1Value === $card2Value;
            
            // Record the win for the host
            $hostPlayerRound->update([
                'status' => 'won',
                // 'win_amount' => $hostUser->amount
            ]);
            
            // Mark all other players as losers
            foreach ($this->playerCards as $playerCard) {
                if ($playerCard->player_id != $hostPlayer->id) {
                    if($hasPair){
                        $playerCard->player->user->wallet->decrement('amount', $playerCard->amount);

                        $playerCard->update([
                            'status' => 'lose',
                            'amount' => $playerCard * 2
                        ]);
                    }else{
                        $playerCard->update([
                            'status' => 'lost',
                        ]);
                    }
                   
                }
            }
            
            session()->flash('message', 'Host wins this round!');
        }

        // Update round status
        $this->round->update(['status' => 'completed']);

        // Start a new round after a short delay
        $this->startNewRound();
    }

    public function startNewRound()
    {
        // Get winners from the previous round to display
        $previousRoundWinners = PlayerRound::with(['player.user'])
            ->where('round_id', $this->round->id)
            ->where('status', 'won')
            ->get()
            ->map(function($winner) {
                return [
                    'name' => $winner->player->user->name,
                    'amount' => (float)$winner->win_amount,
                    'player_id' => $winner->player_id
                ];
            })
            ->values() // Ensure we have a clean array of values
            ->toArray(); // Convert to a plain PHP array
            
        // Create a new round
        $newRound = Round::create([
            'game_id' => $this->game->id,
            'status' => 'ongoing'
        ]);
    
        // Update the current round
        $this->round = $newRound;
    
        $this->resetData();
    
        // Broadcast new round to all players with winner information
        broadcast(new \App\Events\NewRoundStarted(
            $this->game->id, 
            $newRound->id, 
            $previousRoundWinners
        ))->toOthers();
    }

    public function resetData(){

          // Reset game state
          $this->cardsDealt = false;
          $this->showAllCards = false;
          $this->countdown = 10;
          $this->hasPlacedBet = false;
          $this->selectedCards = [];
          $this->selectedPlayer = null;
          $this->playerCards = [];
          $this->round = Round::where('game_id', $this->game->id)
            ->where('status', 'ongoing')
            ->first();

            if (!$this->round) {
                $this->round = Round::create([
                    'game_id' => $this->game->id,
                    'status' => 'ongoing'
                ]);
            }

        session()->flash('message', 'New round started!');

    }


// Add these properties for card selection UI
    public $selectedCards = [];
    public $selectedPlayer = null;
    public $availableCards = [];

    // Add this method after mount() to initialize available cards
    public function initializeCardDeck()
    {
        $suits = ['H', 'D', 'C', 'S'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        $this->availableCards = [];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->availableCards[] = $value . $suit;
            }
        }
    }

    // Add this method to select a card
    // Modify the selectCard method to broadcast card selection
    public function selectCard($card)
    {
        if (!$this->isHost) {
            return;
        }

        if ($this->selectedPlayer && count($this->selectedCards) < 2) {
            $this->selectedCards[] = $card;

            // Broadcast card selection to all players
            $playerName = Player::find($this->selectedPlayer)->user->name;
            broadcast(new \App\Events\CardSelected($this->game->id, $this->selectedPlayer, $card, playerName: $playerName))->toOthers();

            // If we have 2 cards, assign them to the selected player
            if (count($this->selectedCards) == 2) {
                $this->assignCardsToPlayer($this->selectedPlayer, $this->selectedCards);
                $this->selectedCards = [];
            }
        }
    }

    // Modify assignCardsToPlayer to broadcast the final card assignment
    public function assignCardsToPlayer($playerId, $cards)
    {
        if (!$this->isHost || count($cards) != 2) {
            return;
        }

        $player = Player::find($playerId);
        if (!$player) {
            return;
        }

        $playerRound = PlayerRound::where('round_id', $this->round->id)
            ->where('player_id', $player->id)
            ->first();

        if ($playerRound) {
            $playerRound->update([
                'card_1' => $cards[0],
                'card_2' => $cards[1],
                'card_3' => null,
                'score' => $this->calculateScore($cards)
            ]);
        } else {
            $playerRound = PlayerRound::create([
                'round_id' => $this->round->id,
                'player_id' => $player->id,
                'card_1' => $cards[0],
                'card_2' => $cards[1],
                'card_3' => null,
                'score' => $this->calculateScore($cards)
            ]);
        }

        $this->cardsDealt = true;
        $this->loadPlayerCards();

        // Broadcast the final card assignment to all players
        broadcast(new \App\Events\CardsAssigned(
            $this->game->id,
            $player->id,
            $player->user->name,
            $cards,
            $this->calculateScore($cards)
        ))->toOthers();
    }

    // Add this method to select a player
    public function selectPlayer($playerId)
    {
        if (!$this->isHost) {
            return;
        }

        $this->selectedPlayer = $playerId;
        $this->selectedCards = [];
    }



}
