<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class SideQuestCombatGroup implements CombatGroup, Arrayable
{
    protected string $sourceUuid;
    protected AbstractCombatantCollection $combatMinions;

    public function __construct( string $sideQuestUuid, AbstractCombatantCollection $combatMinions)
    {
        $this->sourceUuid = $sideQuestUuid;
        $this->combatMinions = $combatMinions;
    }

    public function getReadyAttacks(int $moment): Collection
    {
        return $this->combatMinions->getReadyAttacks();
    }

    public function getPossibleTargets($moment): CombatantCollection
    {
        return $this->getCombatMinions()->getPossibleTargets();
    }

    /**
     * @return AbstractCombatantCollection
     */
    public function getCombatMinions(): AbstractCombatantCollection
    {
        return $this->combatMinions;
    }

    public function isDefeated()
    {
        return ! $this->combatMinions->hasSurvivors();
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

    public function updateCombatPositions(CombatPositionCollection $combatPositions)
    {
        $this->combatMinions->updateCombatPositions($combatPositions);
    }
}
