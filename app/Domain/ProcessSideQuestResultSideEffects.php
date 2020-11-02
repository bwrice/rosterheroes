<?php


namespace App\Domain;


use App\Aggregates\HeroAggregate;
use App\Aggregates\ItemAggregate;
use App\Aggregates\SquadAggregate;
use App\Domain\Collections\MinionCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class ProcessSideQuestResultSideEffects
{
    /** @var SideQuestResult */
    protected $sideQuestResult;

    /** @var Collection */
    protected $heroAggregates;

    /** @var Collection */
    protected $itemAggregates;

    /** @var SquadAggregate|null */
    protected $squadAggregate;

    /** @var MinionCollection */
    protected $minions;

    public function __construct()
    {
        $this->heroAggregates = collect();
        $this->itemAggregates = collect();
        $this->minions = new MinionCollection();
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @throws \Throwable
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->side_effects_processed_at) {
            throw new \Exception("Side effects already processed for side quest result: " . $sideQuestResult->id);
        }

        if (! $sideQuestResult->combat_processed_at) {
            throw new \Exception("Cannot process side effects because combat not processed for side quest result: " . $sideQuestResult->id);
        }

        $this->sideQuestResult = $sideQuestResult;
        $this->setSquadAggregate($sideQuestResult);

        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();
        $damageTypes = DamageType::all();

        $sideQuestResult->sideQuestEvents()->chunk(100, function (EloquentCollection $sideQuestEvents) use ($combatPositions, $targetPriorities, $damageTypes) {
            $sideQuestEvents->each(function (SideQuestEvent $sideQuestEvent) use ($combatPositions, $targetPriorities, $damageTypes) {

                switch ($sideQuestEvent->event_type) {
                    case SideQuestEvent::TYPE_HERO_DAMAGES_MINION;
                        $this->handleHeroDamagesMinionEvent($sideQuestEvent, $combatPositions, $targetPriorities, $damageTypes);
                        break;
                    case SideQuestEvent::TYPE_HERO_KILLS_MINION;
                        $this->handleHeroKillsMinionEvent($sideQuestEvent, $combatPositions, $targetPriorities, $damageTypes);
                        break;
                    case SideQuestEvent::TYPE_MINION_DAMAGES_HERO;
                        $this->handleMinionDamagesHero($sideQuestEvent, $combatPositions);
                        break;
                    case SideQuestEvent::TYPE_MINION_KILLS_HERO;
                        $this->handleMinionKillsHero($sideQuestEvent, $combatPositions);
                        break;
                    case SideQuestEvent::TYPE_HERO_BLOCKS_MINION:
                        $this->handleHeroBlocksMinion($sideQuestEvent, $combatPositions);
                        break;
                    case SideQuestEvent::TYPE_SIDE_QUEST_VICTORY:
                        $this->squadAggregate->sideQuestVictory($this->sideQuestResult->sideQuest);
                        break;
                    case SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT:
                        $this->squadAggregate->sideQuestDefeat($this->sideQuestResult->sideQuest);
                        break;
                }
            });

            $this->squadAggregate->persist();
            $this->heroAggregates->each(function (HeroAggregate $heroAggregate) {
                $heroAggregate->persist();
            });
            $this->itemAggregates->each(function (ItemAggregate $itemAggregate) {
                $itemAggregate->persist();
            });
        });

        $this->sideQuestResult->side_effects_processed_at = Date::now();
        $this->sideQuestResult->save();
    }

    protected function handleHeroDamagesMinionEvent(
        SideQuestEvent $heroDamagesMinionEvent,
        EloquentCollection $combatPositions,
        EloquentCollection $targetPriorities,
        EloquentCollection $damageTypes)
    {
        $minion = $this->getMinion($heroDamagesMinionEvent, $combatPositions);
        $damage = $heroDamagesMinionEvent->getDamage();
        $this->squadAggregate->dealsDamageToSideQuestMinion($damage, $minion);
        $heroAggregate = $this->getHeroAggregate($heroDamagesMinionEvent, $combatPositions);
        $heroAggregate->dealDamageToSideQuestMinion($damage, $minion);
        $itemAggregate = $this->getItemAggregate($heroDamagesMinionEvent, $combatPositions, $targetPriorities, $damageTypes);
        $itemAggregate->damagesSideQuestMinion($damage, $minion);
    }

    protected function handleHeroKillsMinionEvent(
        SideQuestEvent $heroKillsMinionEvent,
        EloquentCollection $combatPositions,
        EloquentCollection $targetPriorities,
        EloquentCollection $damageTypes)
    {
        $minion = $this->getMinion($heroKillsMinionEvent, $combatPositions);
        $damage = $heroKillsMinionEvent->getDamage();
        $this->squadAggregate->dealsDamageToSideQuestMinion($damage, $minion)
            ->killsSideQuestMinion($minion);
        $heroAggregate = $this->getHeroAggregate($heroKillsMinionEvent, $combatPositions);
        $heroAggregate->dealDamageToSideQuestMinion($heroKillsMinionEvent->getDamage(), $minion)
            ->killsSideQuestMinion($minion);
        $itemAggregate = $this->getItemAggregate($heroKillsMinionEvent, $combatPositions, $targetPriorities, $damageTypes);
        $itemAggregate->damagesSideQuestMinion($damage, $minion)->killsSideQuestMinion($minion);
    }

    protected function handleMinionDamagesHero(
        SideQuestEvent $minionDamagesHeroEvent,
        EloquentCollection $combatPositions)
    {
        $minion = $this->getMinion($minionDamagesHeroEvent, $combatPositions);
        $damage = $minionDamagesHeroEvent->getDamage();
        $this->squadAggregate->takesDamageFromMinion($damage, $minion);
        $heroAggregate = $this->getHeroAggregate($minionDamagesHeroEvent, $combatPositions);
        $heroAggregate->takesDamageFromSideQuestMinion($damage, $minion);
    }

    protected function handleMinionKillsHero(
        SideQuestEvent $minionDamagesHeroEvent,
        EloquentCollection $combatPositions)
    {
        $minion = $this->getMinion($minionDamagesHeroEvent, $combatPositions);
        $damage = $minionDamagesHeroEvent->getDamage();
        $this->squadAggregate->takesDamageFromMinion($damage, $minion)
            ->memberKilledFromSideQuestMinion($minion);
        $heroAggregate = $this->getHeroAggregate($minionDamagesHeroEvent, $combatPositions);
        $heroAggregate->takesDamageFromSideQuestMinion($damage, $minion)
            ->killedBySideQuestMinion($minion);
    }

    protected function handleHeroBlocksMinion(
        SideQuestEvent $minionDamagesHeroEvent,
        EloquentCollection $combatPositions)
    {
        $minion = $this->getMinion($minionDamagesHeroEvent, $combatPositions);
        $this->squadAggregate->memberBlocksSideQuestMinion($minion);
        $heroAggregate = $this->getHeroAggregate($minionDamagesHeroEvent, $combatPositions);
        $heroAggregate->blocksSideQuestMinion($minion);
    }

    protected function getMinion(SideQuestEvent $sideQuestEvent, EloquentCollection $combatPositions)
    {
        $minionUuid = $sideQuestEvent->getCombatMinion($combatPositions)->getSourceUuid();
        $match = $this->minions->first(function (Minion $minion) use ($minionUuid) {
            return ((string)$minion->uuid) === $minionUuid;
        });

        if ($match) {
            return $match;
        }

        $minion = Minion::findUuidOrFail($minionUuid);
        $this->minions->push($minion);
        return $minion;
    }

    protected function getHeroAggregate(SideQuestEvent $sideQuestEvent, EloquentCollection $combatPositions)
    {
        $combatHero = $sideQuestEvent->getCombatHero($combatPositions);
        $heroUuid = $combatHero->getSourceUuid();
        $match = $this->heroAggregates[$heroUuid] ?? null;

        if ($match) {
            return $match;
        }

        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $this->heroAggregates[$heroUuid] = $heroAggregate;
        return $heroAggregate;
    }

    protected function getItemAggregate(SideQuestEvent $sideQuestEvent, EloquentCollection $combatPositions, EloquentCollection $targetPriorities, EloquentCollection $damageTypes)
    {
        $heroCombatAttack = $sideQuestEvent->getHeroCombatAttack($combatPositions, $targetPriorities, $damageTypes);
        $itemUuid = $heroCombatAttack->getItemUuid();
        $match = $this->itemAggregates[$itemUuid] ?? null;

        if ($match) {
            return $match;
        }

        $itemAggregate = ItemAggregate::retrieve($itemUuid);
        $this->itemAggregates[$itemUuid] = $itemAggregate;
        return $itemAggregate;
    }

    protected function setSquadAggregate(SideQuestResult $sideQuestResult)
    {
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $this->squadAggregate = SquadAggregate::retrieve($squad->uuid);
    }
}
