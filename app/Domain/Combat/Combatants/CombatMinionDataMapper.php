<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Attacks\MinionCombatAttackDataMapper;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatMinionDataMapper extends AbstractCombatantDataMapper
{
    /**
     * @var MinionCombatAttackDataMapper
     */
    protected $attackDataMapper;

    public function __construct(MinionCombatAttackDataMapper $attackDataMapper)
    {
        $this->attackDataMapper = $attackDataMapper;
    }

    public function getCombatMinion(array $data, Collection $combatPositions = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();

        $combatAttacks = collect($data['combatAttacks'])->map(function ($attackData) {
            return $this->attackDataMapper->getMinionCombatAttack($attackData);
        });

        $combatMinion =  new CombatMinion(
            $data['minionUuid'],
            $data['combatantUuid'],
            $this->getInitialHealth($data),
            $this->getProtection($data),
            $this->getBlockChancePercent($data),
            $this->getInitialCombatPosition($data, $combatPositions),
            new CombatAttackCollection($combatAttacks)
        );

        $combatMinion = $this->setCombatantCurrentHealth($combatMinion, $data);
        $combatMinion = $this->setInheritedCombatPositions($combatMinion, $data, $combatPositions);

        return $combatMinion;
    }
}
