<?php

namespace App\Events;

use App\Domain\Models\Hero;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class HeroEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Hero
     */
    public $hero;

    /**
     * Create a new event instance.
     *
     * @param Hero $hero
     *
     * @return void
     */
    public function __construct(Hero $hero)
    {
        $this->hero = $hero;
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
}
