<?php

namespace App\Events;

use App\Domain\Models\ProvinceEvent;
use App\Http\Resources\ProvinceEventResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProvinceEventCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected ProvinceEvent $provinceEvent;

    /**
     * ProvinceEventCreated constructor.
     * @param ProvinceEvent $provinceEvent
     */
    public function __construct(ProvinceEvent $provinceEvent)
    {
        $this->provinceEvent = $provinceEvent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('provinces.' . $this->provinceEvent->province->uuid);
    }

    public function broadCastWith()
    {
        return (new ProvinceEventResource($this->provinceEvent))->resolve();
    }

    public function broadcastAs()
    {
        return 'province-event-created';
    }
}
