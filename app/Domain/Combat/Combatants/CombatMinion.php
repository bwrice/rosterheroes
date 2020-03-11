<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\AbstractCombatAttackCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatMinion extends AbstractCombatant
{
    /**
     * @var string
     */
    protected $minionUuid;

    public function __construct(
        string $minionUuid,
        int $health,
        int $protection,
        int $blockChancePercent,
        CombatPosition $combatPosition,
        AbstractCombatAttackCollection $combatAttacks)
    {
        $this->minionUuid = $minionUuid;
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

    protected function getDPS()
    {
        // TODO
        return 1;
    }

    public function toArray()
    {
        return array_merge([
            'minionUuid' => $this->minionUuid
        ], parent::toArray());
    }

    public function getReadyAttacks(): AbstractCombatAttackCollection
    {
        $closestProximityPosition = $this->allCombatPositions()->closestProximity();
        return $this->combatAttacks
            ->withinAttackerProximity($closestProximityPosition->getProximity())
            ->ready();
    }
}
