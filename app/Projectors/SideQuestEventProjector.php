<?php

namespace App\Projectors;

use App\SideQuestEvent;
use App\StorableEvents\BattlefieldSetForSideQuest;
use App\StorableEvents\HeroBlocksMinionSideQuestEvent;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\MinionBlocksHeroSideQuestEvent;
use App\StorableEvents\MinionDamagesHeroSideQuestEvent;
use App\StorableEvents\MinionKillsHeroSideQuestEvent;
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
                'attackUuid' => $event->attackUuid,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid,
                'damage' => $event->damage,
                'stamina_cost' => $event->staminaCost,
                'mana_cost' => $event->manaCost
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
                'attackUuid' => $event->attackUuid,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid,
                'damage' => $event->damage,
                'stamina_cost' => $event->staminaCost,
                'mana_cost' => $event->manaCost
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
                'attackUuid' => $event->attackUuid,
                'heroUuid' => $event->heroUuid,
                'itemUuid' => $event->itemUuid,
                'minionUuid' => $event->minionUuid,
                'stamina_cost' => $event->staminaCost,
                'mana_cost' => $event->manaCost
            ]
        ]);
    }

    public function onMinionDamagesHero(MinionDamagesHeroSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_MINION_DAMAGES_HERO,
            'data' => $event->data
        ]);
    }

    public function onMinionKillsHero(MinionKillsHeroSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_MINION_KILLS_HERO,
            'data' => $event->data
        ]);
    }

    public function onHeroBlocksMinion(HeroBlocksMinionSideQuestEvent $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => SideQuestEvent::TYPE_HERO_BLOCKS_MINION,
            'data' => $event->data
        ]);
    }

    public function onBattlefieldSet(BattlefieldSetForSideQuest $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => 0,
            'event_type' => SideQuestEvent::TYPE_BATTLEGROUND_SET,
            'data' => $event->eventData
        ]);
    }
}