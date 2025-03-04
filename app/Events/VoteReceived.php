<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class VoteReceived implements ShouldBroadcastNow
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
        return new Channel('tugofwar');
    }

    public function broadcastAs()
    {
        return 'vote.received';
    }
}
