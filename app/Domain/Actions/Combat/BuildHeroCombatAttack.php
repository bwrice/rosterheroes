<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class BuildHeroCombatAttack
{
    /**
     * @var BuildCombatAttack
     */
    protected $buildCombatAttack;

    public function __construct(BuildCombatAttack $buildCombatAttack)
    {
        $this->buildCombatAttack = $buildCombatAttack;
    }

    public function execute(
        Attack $attack,
        Item $item,
        Hero $hero,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatAttack = $this->buildCombatAttack->execute($attack, $hero, $combatPositions, $targetPriorities, $damageTypes);
        return new HeroCombatAttack(
            $hero->uuid,
            $item->uuid,
            $combatAttack,
            $attack->getResourceCostCollection()
        );
    }
}
