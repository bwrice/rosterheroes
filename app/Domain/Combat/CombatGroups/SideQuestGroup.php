<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class SideQuestGroup implements CombatGroup, Arrayable
{
    /**
     * @var string
     */
    protected $sideQuestName;
    /**
     * @var string
     */
    protected $sideQuestUuid;
    /**
     * @var AbstractCombatantCollection
     */
    protected $combatMinions;

    public function __construct(string $sideQuestName, string $sideQuestUuid, AbstractCombatantCollection $combatMinions)
    {
        $this->sideQuestName = $sideQuestName;
        $this->sideQuestUuid = $sideQuestUuid;
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
            'sideQuestName' => $this->sideQuestName,
            'sideQuestUuid' => $this->sideQuestUuid,
            'combatMinions' => $this->combatMinions->toArray()
        ];
    }

    public function updateCombatPositions(CombatPositionCollection $combatPositions)
    {
        $this->combatMinions->updateCombatPositions($combatPositions);
    }
}
