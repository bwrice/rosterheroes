<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CombatSquad implements CombatGroup, Arrayable
{
    protected string $squadName;
    protected string $sourceUuid;
    protected int $experience, $squadRankID;
    protected AbstractCombatantCollection $combatHeroes;

    public function __construct(string $sourceUuid, int $experience, int $squadRankID, AbstractCombatantCollection $combatHeroes)
    {
        $this->sourceUuid = $sourceUuid;
        $this->experience = $experience;
        $this->squadRankID = $squadRankID;
        $this->combatHeroes = $combatHeroes;
    }

    public function getReadyAttacks(int $moment): Collection
    {
        return $this->combatHeroes->getReadyAttacks();
    }

    /**
     * @param $moment
     * @return AbstractCombatantCollection
     */
    public function getPossibleTargets($moment): CombatantCollection
    {
        return $this->combatHeroes->getPossibleTargets();
    }

    /**
     * @return string
     */
    public function getSourceUuid(): string
    {
        return $this->sourceUuid;
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

    public function isDefeated(int $moment): bool
    {
        return ! $this->combatHeroes->hasSurvivors();
    }


    public function updateCombatPositions(CombatPositionCollection $combatPositions)
    {
        $this->combatHeroes->updateCombatPositions($combatPositions);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'squadName' => $this->squadName,
            'squadUuid' => $this->sourceUuid,
            'experience' => $this->experience,
            'combatHeroes' => $this->combatHeroes->toArray()
        ];
    }

    /**
     * @return string
     */
    public function getSquadName(): string
    {
        return $this->squadName;
    }

    /**
     * @return int
     */
    public function getSquadRankID(): int
    {
        return $this->squadRankID;
    }
}
