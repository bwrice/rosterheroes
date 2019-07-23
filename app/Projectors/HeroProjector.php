<?php

namespace App\Projectors;

use App\StorableEvents\HeroCreated;
use App\Domain\Models\Hero;
use App\StorableEvents\HeroSlotCreated;
use App\StorableEvents\PlayerSpiritAddedToHero;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class HeroProjector implements Projector
{
    use ProjectsEvents;

    public function onHeroCreated(HeroCreated $event, string $aggregateUuid)
    {
        Hero::query()->create([
            'uuid' => $aggregateUuid,
            'name' => $event->name,
            'hero_class_id' => $event->heroClassID,
            'hero_race_id' => $event->heroRaceID,
            'hero_rank_id' => $event->heroRankID
        ]);
    }

    public function onHeroSlotCreated(HeroSlotCreated $event, string $aggregateUuid)
    {
        $hero = Hero::uuid($aggregateUuid);
        $hero->slots()->create([
            'slot_type_id' => $event->slotTypeID
        ]);
    }

    public function onPlayerSpiritAddedToHero(PlayerSpiritAddedToHero $event, string $aggregateUuid)
    {
        $hero = Hero::uuid($aggregateUuid);
        $hero->player_spirit_id = $event->playerSpiritID;
        $hero->save();
    }
}