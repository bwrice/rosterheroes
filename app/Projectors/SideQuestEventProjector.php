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
use App\StorableEvents\SideQuestDefeat;
use App\StorableEvents\StorableSideQuestEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class SideQuestEventProjector implements Projector
{
    use ProjectsEvents;

    public function onHeroDamagesMinion(HeroDamagesMinionSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_HERO_DAMAGES_MINION);
    }

    public function onHeroKillsMinion(HeroKillsMinionSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_HERO_KILLS_MINION);
    }

    public function onMinionBlocksHero(MinionBlocksHeroSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_MINION_BLOCKS_HERO);
    }

    public function onMinionDamagesHero(MinionDamagesHeroSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_MINION_DAMAGES_HERO);
    }

    public function onMinionKillsHero(MinionKillsHeroSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_MINION_KILLS_HERO);
    }

    public function onHeroBlocksMinion(HeroBlocksMinionSideQuestEvent $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_HERO_BLOCKS_MINION);
    }

    public function onBattlefieldSet(BattlefieldSetForSideQuest $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_BATTLEGROUND_SET);
    }

    public function onSideQuestDefeat(SideQuestDefeat $event, string $aggregateUuid)
    {
        $this->createSideQuestEvent($event, $aggregateUuid,SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT);
    }

    protected function createSideQuestEvent(StorableSideQuestEvent $event, string $aggregateUuid, string $eventType)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'event_type' => $eventType,
            'data' => $event->data
        ]);
    }
}
