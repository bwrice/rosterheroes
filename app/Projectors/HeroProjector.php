<?php

namespace App\Projectors;

use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
use App\Domain\Combat\Combatants\CombatHeroDataMapper;
use App\Domain\Models\Item;
use App\StorableEvents\HeroCreated;
use App\Domain\Models\Hero;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\MinionDamagesHeroSideQuestEvent;
use App\StorableEvents\MinionKillsHeroSideQuestEvent;
use App\StorableEvents\UpdateHeroPlayerSpirit;
use App\StorableEvents\WeeklyPlayerSpiritClearedFromHero;
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

    public function onWeeklyPlayerSpiritClearedFromHero(WeeklyPlayerSpiritClearedFromHero $event, string $aggregateUuid)
    {
        $hero = Hero::findUuid($aggregateUuid);
        $hero->player_spirit_id = null;
        $hero->save();
    }

    public function onHeroDamagesMinionSideQuestEvent(HeroDamagesMinionSideQuestEvent $event)
    {
        $heroCombatAttack = $this->getHeroCombatAttackDataMapper()
            ->getHeroCombatAttack($event->getHeroCombatAttackData());

        $hero = Hero::findUuidOrFail($heroCombatAttack->getHeroUuid());
        $hero->damage_dealt += $event->getDamage();
        $hero->save();
    }

    public function onHeroKillsMinionSideQuestEvent(HeroKillsMinionSideQuestEvent $event)
    {
        $heroCombatAttack = $this->getHeroCombatAttackDataMapper()
            ->getHeroCombatAttack($event->getHeroCombatAttackData());

        $hero = Hero::findUuidOrFail($heroCombatAttack->getHeroUuid());
        $hero->damage_dealt += $event->getDamage();
        $hero->minion_kills++;
        $hero->save();
    }

    public function onMinionDamagesHeroSideQuestEvent(MinionDamagesHeroSideQuestEvent $event)
    {
        $hero = Hero::findUuidOrFail($event->getHeroUuid());
        $hero->damage_taken += $event->getDamage();
        $hero->save();
    }

    public function onMinionKillsHeroSideQuestEvent(MinionKillsHeroSideQuestEvent $event)
    {
        $hero = Hero::findUuidOrFail($event->getHeroUuid());
        $hero->damage_taken += $event->getDamage();
        $hero->save();
    }

    /**
     * @return HeroCombatAttackDataMapper
     */
    protected function getHeroCombatAttackDataMapper()
    {
        return app(HeroCombatAttackDataMapper::class);
    }

    protected function getCombatHeroDataMapper()
    {
        return app(CombatHeroDataMapper::class);
    }
}
