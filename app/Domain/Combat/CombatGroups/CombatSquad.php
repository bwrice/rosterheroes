<?php


namespace App\Domain\Combat\CombatGroups;

use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\CombatHero;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CombatSquad implements CombatGroup, Arrayable
{
    protected string $sourceUuid;
    protected int $experience, $squadRankID;
    protected Collection $combatHeroes;

    public function __construct(string $sourceUuid, int $experience, int $squadRankID, Collection $combatHeroes)
    {
        $this->sourceUuid = $sourceUuid;
        $this->experience = $experience;
        $this->squadRankID = $squadRankID;
        $this->combatHeroes = $combatHeroes;
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

    public function isDefeated(int $moment): bool
    {
        return $this->getSurvivingHeroes()->isEmpty();
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
            'squad_snapshot_uuid' => $this->sourceUuid,
            'experience' => $this->experience,
            'rank_id' => $this->squadRankID,
            'combat_heroes' => $this->combatHeroes->toArray()
        ];
    }

    /**
     * @return int
     */
    public function getSquadRankID(): int
    {
        return $this->squadRankID;
    }

    public function getPossibleAttackers($moment): Collection
    {
        return $this->getSurvivingHeroes();
    }

    public function getPossibleTargets($moment): Collection
    {
        return $this->getSurvivingHeroes();
    }

    protected function getSurvivingHeroes()
    {
        return $this->combatHeroes->filter(function (CombatHero $combatHero) {
            return $combatHero->getCurrentHealth() > 0;
        });
    }
}
