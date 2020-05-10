<?php

namespace App\Projectors;

use App\Domain\Models\Item;
use App\StorableEvents\ItemDamagesSideQuestMinion;
use App\StorableEvents\ItemKillsSideQuestMinion;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class ItemCombatStatsProjector implements Projector
{
    use ProjectsEvents;

    public function onItemDamagesMinion(ItemDamagesSideQuestMinion $event, string $aggregateUuid)
    {
        $item = Item::findUuidOrFail($aggregateUuid);
        $item->damage_dealt += $event->damage;
        $item->minion_damage_dealt += $event->damage;
        $item->side_quest_damage_dealt += $event->damage;
        $item->save();
    }

    public function onItemKillsMinion(ItemKillsSideQuestMinion $event, string $aggregateUuid)
    {
        $item = Item::findUuidOrFail($aggregateUuid);
        $item->combat_kills++;
        $item->minion_kills++;
        $item->side_quest_kills++;
        $item->save();
    }
}
