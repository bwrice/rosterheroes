<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class SideQuestGroup implements CombatGroup, Arrayable
{
    /**
     * @var CombatantCollection
     */
    protected $combatMinions;

    public function __construct(CombatantCollection $combatMinions)
    {
        $this->combatMinions = $combatMinions;
    }

    public function getReadyAttacks(int $moment): Collection
    {
        // TODO: Implement getReadyAttacks() method.
    }

    public function getPossibleTargets($moment): CombatantCollection
    {
        // TODO: Implement getPossibleTargets() method.
    }

    /**
     * @return CombatantCollection
     */
    public function getCombatMinions(): CombatantCollection
    {
        return $this->combatMinions;
    }

    public function isDefeated()
    {
        return $this->combatMinions->hasSurvivors();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'combatMinions' => $this->combatMinions->toArray()
        ];
    }
}
