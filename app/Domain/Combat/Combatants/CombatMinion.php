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
    /**
     * @var string
     */
    protected $minionUuid;
    /**
     * @var string
     */
    protected $combatantUuid;

    public function __construct(
        string $minionUuid,
        string $combatantUuid,
        int $health,
        int $protection,
        int $blockChancePercent,
        CombatPosition $combatPosition,
        CombatAttackCollection $combatAttacks)
    {
        $this->minionUuid = $minionUuid;
        // We need a local uuid because a quest/side-quest can have multiples of the same minion
        $this->combatantUuid = $combatantUuid;
        parent::__construct(
            $health,
            $protection,
            $blockChancePercent,
            $combatPosition,
            $combatAttacks
        );
    }

    /**
     * @return string
     */
    public function getMinionUuid(): string
    {
        return $this->minionUuid;
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
            'minionUuid' => $this->minionUuid,
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
        return Minion::findUuidOrFail($this->getMinionUuid());
    }
}
