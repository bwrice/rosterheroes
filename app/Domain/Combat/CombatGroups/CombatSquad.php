<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CombatSquad implements CombatGroup
{
    /**
     * @var int
     */
    protected $squadID;
    /**
     * @var int
     */
    protected $experience;
    /**
     * @var AbstractCombatantCollection
     */
    protected $combatHeroes;

    public function __construct(int $squadID, int $experience, AbstractCombatantCollection $combatHeroes)
    {
        $this->squadID = $squadID;
        $this->experience = $experience;
        $this->combatHeroes = $combatHeroes;
    }

    public function getReadyAttacks(int $moment): Collection
    {
        $attacks = collect();
        $this->combatHeroes->each(function (CombatHero $combatHero) use (&$attacks, $moment) {
            $attacks = $attacks->merge($combatHero->getReadyAttacks($moment));
        });
        return $attacks;
    }

    /**
     * @param $moment
     * @return AbstractCombatantCollection
     */
    public function getPossibleTargets($moment): CombatantCollection
    {
        $alive = $this->combatHeroes->filter(function (CombatHero $combatHero) {
            return $combatHero->getCurrentHealth() > 0;
        });
        return $alive;
    }

    /**
     * @return int
     */
    public function getSquadID(): int
    {
        return $this->squadID;
    }

    /**
     * @return int
     */
    public function getExperience(): int
    {
        return $this->experience;
    }

    /**
     * @return AbstractCombatantCollection
     */
    public function getCombatHeroes(): AbstractCombatantCollection
    {
        return $this->combatHeroes;
    }

    public function isDefeated()
    {
        return $this->combatHeroes->hasSurvivors();
    }


    public function updateCombatPositions(EloquentCollection $combatPositions)
    {
        $heroCombatPositions = $this->combatHeroes->unique(function (CombatHero $combatHero) {
            return $combatHero->getInitialCombatPosition();
        });
        $heroCombatPositionIDs = $heroCombatPositions->pluck('id');
        $missing = $combatPositions->reject(function (CombatPosition $combatPosition) use ($heroCombatPositionIDs) {
            return $heroCombatPositionIDs->contains($combatPosition->id);
        });
    }
}
