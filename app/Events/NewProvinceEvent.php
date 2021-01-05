<?php

namespace App\Events;

use App\Domain\Models\ProvinceEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProvinceEvent implements ShouldBroadcast
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
        $channels[] = new Channel('provinces.' . $this->provinceEvent->province->uuid);
        if ($this->provinceEvent->isGlobalEvent()) {
            $channels[] = new Channel('provinces.global');
        }
        return $channels;
    }

    public function broadCastWith()
    {
        return [
            'uuid' => $this->provinceEvent->uuid
        ];
    }

    public function broadcastAs()
    {
        return 'new-province-event';
    }
}
