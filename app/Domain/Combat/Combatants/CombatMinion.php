<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Minion;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatMinion extends AbstractCombatant
{
    protected string $sourceUuid;
    protected string $combatantUuid;

    public function __construct(
        string $sourceUuid,
        int $health,
        int $protection,
        float $blockChancePercent,
        int $combatPositionID,
        CombatAttackCollection $combatAttacks)
    {
        $this->sourceUuid = $sourceUuid;
        // We need a local uuid because a quest/side-quest can have multiples of the same minion
        $this->combatantUuid = (string) Str::uuid();
        parent::__construct(
            $health,
            $protection,
            $blockChancePercent,
            $combatPositionID,
            $combatAttacks
        );
    }

    /**
     * @return string
     */
    public function getSourceUuid(): string
    {
        return $this->sourceUuid;
    }

    /**
     * @return string
     */
    public function getCombatantUuid(): string
    {
        return $this->combatantUuid;
    }

    protected function getDPS()
    {
        // TODO
        return 1;
    }

    public function toArray()
    {
        return array_merge([
            'minionUuid' => $this->sourceUuid,
            'combatantUuid' => $this->combatantUuid
        ], parent::toArray());
    }

    public function getReadyAttacks(): CombatAttackCollection
    {
        $closestProximityPosition = $this->allCombatPositions()->closestProximity();
        return $this->combatAttacks
            ->withinAttackerProximity($closestProximityPosition->getProximity())
            ->ready();
    }

    public function getMinion()
    {
        return Minion::findUuidOrFail($this->getSourceUuid());
    }
}
