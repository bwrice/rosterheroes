<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Combat\Combatants\Combatant;
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
            'side_quest_snapshot_uuid' => $this->sourceUuid,
            'combat_minions' => $this->combatMinions->toArray()
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
        return $this->combatMinions->filter(function (Combatant $combatMinion) {
            return $combatMinion->getCurrentHealth() > 0;
        });
    }
}
