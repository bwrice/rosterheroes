<?php

namespace App\Projectors;

use App\Domain\Models\Item;
use App\StorableEvents\ItemDamagesMinion;
use App\StorableEvents\ItemKillsMinion;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class ItemCombatStatsProjector implements Projector
{
    use ProjectsEvents;

    public function onItemDamagesMinion(ItemDamagesMinion $event, string $aggregateUuid)
    {
        $item = Item::findUuidOrFail($aggregateUuid);
        $item->damage_dealt += $event->damage;
        $item->save();
    }

    public function onItemKillsMinion(ItemKillsMinion $event, string $aggregateUuid)
    {
        $item = Item::findUuidOrFail($aggregateUuid);
        $item->minion_kills++;
        $item->save();
    }
}
