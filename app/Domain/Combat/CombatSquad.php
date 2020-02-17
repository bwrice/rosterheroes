<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatHeroCollection;
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
     * @var CombatHeroCollection
     */
    protected $combatHeroes;

    public function __construct(int $squadID, int $experience, CombatHeroCollection $combatHeroes)
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

    public function getPossibleTargets($moment): CombatHeroCollection
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
     * @return CombatHeroCollection
     */
    public function getCombatHeroes(): CombatHeroCollection
    {
        return $this->combatHeroes;
    }
}
