<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewRoundStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Change to protected properties
    public $gameId;
    public $roundId;
    public $winners;
    
    /**
     * Create a new event instance.
     */
    public function __construct($gameId, $roundId, $winners)
    {
        $this->gameId = $gameId;
        $this->roundId = $roundId;
        
        // Ensure winners is a plain array and convert any objects to arrays
        $this->winners = $winners;
        
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