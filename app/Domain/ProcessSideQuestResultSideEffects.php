<?php


namespace App\Domain;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Minion;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class ProcessSideQuestResultSideEffects
{
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

        $sideQuestResult->sideQuestEvents()->chunk(100, function (Collection $sideQuestEvents) use ($combatPositions) {
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

    protected function handleHeroDamagesMinionEvent(SideQuestEvent $heroDamagesMinionEvent, Collection $combatPositions)
    {
        $this->handHeroDealsDamageEvent($heroDamagesMinionEvent, $combatPositions);
    }

    protected function handleHeroKillsMinionEvent(SideQuestEvent $heroDamagesMinionEvent, Collection $combatPositions)
    {
        $this->handHeroDealsDamageEvent($heroDamagesMinionEvent, $combatPositions);
    }

    /**
     * @param SideQuestEvent $heroDamagesMinionEvent
     * @param Collection $combatPositions
     */
    protected function handHeroDealsDamageEvent(SideQuestEvent $heroDamagesMinionEvent, Collection $combatPositions): void
    {
        $combatHero = $heroDamagesMinionEvent->getCombatHero($combatPositions);
        $heroUuid = $combatHero->getHeroUuid();
        $minion = Minion::findUuidOrFail($heroDamagesMinionEvent->getCombatMinion()->getMinionUuid());
        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $heroAggregate->dealDamageToMinion($heroDamagesMinionEvent->getDamage(), $minion)->persist();
    }
}
