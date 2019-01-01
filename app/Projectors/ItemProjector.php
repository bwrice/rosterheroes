<?php

namespace App\Projectors;

use App\Events\ItemCreationRequested;
use App\Item;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class ItemProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        ItemCreationRequested::class => 'onItemCreationRequested'
    ];

    public function onItemCreationRequested(ItemCreationRequested $event)
    {
        Item::create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'itemUuid';
    }
}