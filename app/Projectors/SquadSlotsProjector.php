<?php

namespace App\Projectors;

use App\Domain\Collections\SlotCollection;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Slot;
use App\StorableEvents\SquadSlotsAdded;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class SquadSlotsProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadSlotsAdded(SquadSlotsAdded $event, string $aggregateUuid)
    {
        /** @var SlotType $slotType */
        $slotType = SlotType::query()->where('name', '=', $event->slotTypeName)->first();

        $squad = Squad::findUuid($aggregateUuid);
        $slots = new SlotCollection();

        foreach(range(1, $event->count) as $slotCount) {
            $slots->push(Slot::query()->make([
                'slot_type_id' => $slotType->id
            ]));
        }

        $squad->slots()->createMany($slots->toArray());
    }
}
