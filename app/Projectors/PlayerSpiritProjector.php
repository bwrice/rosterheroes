<?php

namespace App\Projectors;

use App\Domain\Models\PlayerSpirit;
use App\Events\PlayerSpiritCreationRequested;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class PlayerSpiritProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        PlayerSpiritCreationRequested::class => 'onPlayerSpiritCreationRequested'
    ];

    public function onPlayerSpiritCreationRequested(PlayerSpiritCreationRequested $event)
    {
        PlayerSpirit::query()->create($event->attributes);
    }


    public function streamEventsBy()
    {
        return 'playerSpiritUuid';
    }
}