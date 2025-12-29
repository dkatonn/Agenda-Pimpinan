<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TvUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function broadcastOn(): Channel
    {
        return new Channel('tv-channel');
    }

    public function broadcastAs(): string
    {
        return 'tv.updated';
    }
}
