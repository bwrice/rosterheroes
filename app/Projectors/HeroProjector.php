<?php

namespace App\Projectors;

use App\Domain\Models\Item;
use App\StorableEvents\HeroCreated;
use App\Domain\Models\Hero;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\UpdateHeroPlayerSpirit;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class HeroProjector implements Projector
{
    use ProjectsEvents;

    public function onHeroCreated(HeroCreated $event, string $aggregateUuid)
    {
        Hero::query()->create([
            'uuid' => $aggregateUuid,
            'name' => $event->name,
            'squad_id' => $event->squadID,
            'hero_class_id' => $event->heroClassID,
            'hero_race_id' => $event->heroRaceID,
            'hero_rank_id' => $event->heroRankID,
            'combat_position_id' => $event->combatPositionID
        ]);
    }

    public function onUpdateHeroPlayerSpirit(UpdateHeroPlayerSpirit $event, string $aggregateUuid)
    {
        $hero = Hero::findUuid($aggregateUuid);
        $hero->player_spirit_id = $event->playerSpiritID;
        $hero->save();
    }

    public function onHeroDamagesMinionSideQuestEvent(HeroDamagesMinionSideQuestEvent $event)
    {
        $hero = Hero::findUuidOrFail($event->heroUuid);
        $hero->damage_dealt += $event->damage;
        $hero->save();
    }
}
