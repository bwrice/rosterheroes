<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class BuildHeroCombatAttack extends AbstractBuildCombatAttack
{

    public function execute(
        Attack $attack,
        Item $item,
        Hero $hero,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $damage = $this->calculateAttackDamage($attack, $hero);
        return new HeroCombatAttack(
            $hero->uuid,
            $item->uuid,
            $attack->name,
            $attack->uuid,
            $damage,
            $attack->getCombatSpeed(),
            $attack->getGrade(),
            $attack->getMaxTargetsCount(),
            $this->getAttackerPosition($attack, $combatPositions),
            $this->getTargetPosition($attack, $combatPositions),
            $this->getTargetPriority($attack, $targetPriorities),
            $this->getDamageType($attack, $damageTypes),
            $attack->getResourceCostCollection()
        );
    }
}
