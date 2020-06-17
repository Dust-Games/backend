<?php

namespace App\Events;

use App\Models\LeagueRow;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeagueSaved implements iGetModel
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $league;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LeagueRow $league)
    {
        $this->league = $league;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getModel()
    {
        return $this->league;
    }
}
