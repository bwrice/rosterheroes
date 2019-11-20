<?php

namespace App\Projectors;

use App\Events\ItemBlueprintCreated;
use App\Domain\Models\ItemBlueprint;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

class ItemBlueprintProjector implements QueuedProjector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        ItemBlueprintCreated::class => 'onCreated'
    ];

    public function onCreated(ItemBlueprintCreated $event)
    {
        ItemBlueprint::create($event->itemBlueprintAttributes);
    }

//    public function streamEventsBy()
//    {
//        return 'item_blueprint_id';
//    }
}
