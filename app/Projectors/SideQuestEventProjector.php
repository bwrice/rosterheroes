<?php

namespace App\Projectors;

use App\SideQuestEvent;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\MinionBlocksHeroSideQuestEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class SideQuestEventProjector implements Projector
{
    use ProjectsEvents;

    public function onHeroDamagesMinion(HeroDamagesMinionSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_HERO_DAMAGES_MINION,
            'data' => [
                'attackID' => $event->attackID,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid,
                'damage' => $event->damage
            ]
        ]);
    }

    public function onHeroKillsMinion(HeroKillsMinionSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_HERO_KILLS_MINION,
            'data' => [
                'attackID' => $event->attackID,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid,
                'damage' => $event->damage
            ]
        ]);
    }

    public function onMinionBlocksHero(MinionBlocksHeroSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_MINION_BLOCKS_HERO,
            'data' => [
                'attackID' => $event->attackID,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid
            ]
        ]);
    }
}
