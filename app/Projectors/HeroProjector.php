<?php

namespace App\Projectors;

use App\Events\HeroCreationRequested;
use App\Domain\Models\Hero;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class HeroProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        HeroCreationRequested::class => 'onHeroCreationRequested'
    ];

    public function onHeroCreationRequested(HeroCreationRequested $event)
    {
        Hero::query()->create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'heroUuid';
    }
}