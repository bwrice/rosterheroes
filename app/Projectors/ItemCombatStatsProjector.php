<?php

namespace App\Projectors;

use App\Domain\Models\Item;
use App\StorableEvents\ItemDamagesSideQuestMinion;
use App\StorableEvents\ItemKillsSideQuestMinion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

final class ItemCombatStatsProjector extends Projector implements ShouldQueue
{

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
