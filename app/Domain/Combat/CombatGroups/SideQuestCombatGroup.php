<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\CombatMinion;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class SideQuestCombatGroup implements CombatGroup, Arrayable
{
    protected string $sourceUuid;
    protected Collection $combatMinions;

    public function __construct(string $sideQuestUuid, Collection $combatMinions)
    {
        $this->sourceUuid = $sideQuestUuid;
        $this->combatMinions = $combatMinions;
    }

    public function isDefeated(int $moment): bool
    {
        return $this->getSurvivors()->isEmpty();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'sideQuestUuid' => $this->sourceUuid,
            'combatMinions' => $this->combatMinions->toArray()
        ];
    }

    /**
     * @return string
     */
    public function getSourceUuid(): string
    {
        return $this->sourceUuid;
    }

    public function getPossibleAttackers($moment): Collection
    {
        return $this->getSurvivors();
    }

    public function getPossibleTargets($moment): Collection
    {
        return $this->getSurvivors();
    }

    public function getSurvivors()
    {
        return $this->combatMinions->filter(function (CombatMinion $combatMinion) {
            return $combatMinion->getCurrentHealth() > 0;
        });
    }
}
