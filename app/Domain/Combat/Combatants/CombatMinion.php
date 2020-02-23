<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatMinion extends AbstractCombatant
{
    /**
     * @var int
     */
    protected $minionID;

    public function __construct(
        int $minionID,
        int $health,
        int $protection,
        int $blockChancePercent,
        CombatPosition $combatPosition,
        Collection $combatAttacks)
    {
        $this->minionID = $minionID;
        parent::__construct(
            $health,
            $protection,
            $blockChancePercent,
            $combatPosition,
            $combatAttacks
        );
    }

    /**
     * @return int
     */
    public function getMinionID(): int
    {
        return $this->minionID;
    }

    protected function getDPS()
    {
        // TODO
        return 1;
    }
}
