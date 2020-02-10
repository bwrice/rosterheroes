<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
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
     * @var CombatantCollection
     */
    protected $combatHeroes;

    public function __construct(int $squadID, int $experience, CombatantCollection $combatHeroes)
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
     * @return CombatantCollection
     */
    public function getCombatHeroes(): CombatantCollection
    {
        return $this->combatHeroes;
    }
}
