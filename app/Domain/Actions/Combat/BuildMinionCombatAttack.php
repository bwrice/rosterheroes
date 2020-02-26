<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Minion;
use Illuminate\Database\Eloquent\Collection;

class BuildMinionCombatAttack
{
    /**
     * @var BuildCombatAttack
     */
    protected $buildCombatAttack;

    public function __construct(BuildCombatAttack $buildCombatAttack)
    {
        $this->buildCombatAttack = $buildCombatAttack;
    }

    /**
     * @param Attack $attack
     * @param Minion $minion
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return MinionCombatAttack
     */
    public function execute(
        Attack $attack,
        Minion $minion,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatAttack = $this->buildCombatAttack->execute($attack, $minion, $combatPositions, $targetPriorities, $damageTypes);
        return new MinionCombatAttack($minion->uuid, $combatAttack);
    }
}
