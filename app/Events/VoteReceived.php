<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteReceived implements ShouldBroadcast
{
    use SerializesModels;

    public $option;
    public $voteCounts;

    public function __construct($option, $voteCounts)
    {
        $this->option = $option;
        $this->voteCounts = $voteCounts;
    }

    public function broadcastOn()
    {
        return new Channel('cabo-de-guerra');
    }

    public function broadcastAs()
    {
        return 'vote.received';
    }
}
