<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardsAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $playerId;
    public $playerName;
    public $cards;
    public $score;

    /**
     * Create a new event instance.
     */
    public function __construct($gameId, $playerId, $playerName, $cards, $score)
    {
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->playerName = $playerName;
        $this->cards = $cards;
        $this->score = $score;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('game.' . $this->gameId),
        ];
    }
}