<?php


namespace App\Domain;


use App\Aggregates\HeroAggregate;
use App\Domain\Collections\MinionCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Minion;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class ProcessSideQuestResultSideEffects
{
    /** @var Collection */
    protected $heroAggregates;

    /** @var MinionCollection */
    protected $minions;

    public function __construct()
    {
        $this->heroAggregates = collect();
        $this->minions = new MinionCollection();
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @throws \Exception
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->side_effects_processed_at) {
            throw new \Exception("Side effects already processed for side quest result: " . $sideQuestResult->id);
        }

        if (! $sideQuestResult->combat_processed_at) {
            throw new \Exception("Cannot process side effects because combat not processed for side quest result: " . $sideQuestResult->id);
        }

        $combatPositions = CombatPosition::all();

        $sideQuestResult->sideQuestEvents()->chunk(100, function (EloquentCollection $sideQuestEvents) use ($combatPositions) {
            $sideQuestEvents->each(function (SideQuestEvent $sideQuestEvent) use ($combatPositions) {

                switch ($sideQuestEvent->event_type) {
                    case SideQuestEvent::TYPE_HERO_DAMAGES_MINION;
                        $this->handleHeroDamagesMinionEvent($sideQuestEvent, $combatPositions);
                        break;
                    case SideQuestEvent::TYPE_HERO_KILLS_MINION;
                        $this->handleHeroKillsMinionEvent($sideQuestEvent, $combatPositions);
                        break;
                }
            });
        });

        $sideQuestResult->side_effects_processed_at = Date::now();
        $sideQuestResult->save();
    }

    protected function handleHeroDamagesMinionEvent(SideQuestEvent $heroDamagesMinionEvent, EloquentCollection $combatPositions)
    {
        $this->handHeroDealsDamageEvent($heroDamagesMinionEvent, $combatPositions);
    }

    protected function handleHeroKillsMinionEvent(SideQuestEvent $heroDamagesMinionEvent, EloquentCollection $combatPositions)
    {
        $this->handHeroDealsDamageEvent($heroDamagesMinionEvent, $combatPositions);
    }

    /**
     * @param SideQuestEvent $heroDamagesMinionEvent
     * @param EloquentCollection $combatPositions
     */
    protected function handHeroDealsDamageEvent(SideQuestEvent $heroDamagesMinionEvent, EloquentCollection $combatPositions): void
    {
        $combatHero = $heroDamagesMinionEvent->getCombatHero($combatPositions);
        $heroUuid = $combatHero->getHeroUuid();
        $minion = $this->getMinion($heroDamagesMinionEvent->getCombatMinion()->getMinionUuid());
        $heroAggregate = $this->getHeroAggregate($heroUuid);
        $heroAggregate->dealDamageToMinion($heroDamagesMinionEvent->getDamage(), $minion)->persist();
    }

    protected function getMinion(string $minionUuid)
    {
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

    protected function getHeroAggregate(string $heroUuid)
    {
        $match = $this->heroAggregates[$heroUuid] ?? null;

        if ($match) {
            return $match;
        }

        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $this->heroAggregates[$heroUuid] = $heroAggregate;
        return $heroAggregate;
    }
}
