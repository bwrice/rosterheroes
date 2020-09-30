<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BuildHeroCombatAttack
 * @package App\Domain\Actions\Combat
 * @deprecated
 */
class BuildHeroCombatAttack extends AbstractBuildCombatAttack
{
    public function execute(
        Attack $attack,
        Item $item,
        Hero $hero,
        float $fantasyPower,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $item->setUsesItems($hero);
        $attack->setHasAttacks($item);
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $damage = $this->calculateCombatDamage->execute($attack, $fantasyPower);
        return new HeroCombatAttack(
            $hero->uuid,
            $item->uuid,
            $attack->name,
            $attack->uuid,
            $damage,
            $attack->getCombatSpeed(),
            $attack->tier,
            $attack->getMaxTargetsCount(),
            $this->getAttackerPosition($attack, $combatPositions),
            $this->getTargetPosition($attack, $combatPositions),
            $this->getTargetPriority($attack, $targetPriorities),
            $this->getDamageType($attack, $damageTypes),
            $attack->getResourceCosts()
        );
    }
}
