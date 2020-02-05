<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\TargetPriority;
use App\Facades\FantasyPower;
use Illuminate\Support\Collection;

class BuildCombatHeroAction
{
    public function execute(Hero $hero, Collection $combatPositions, Collection $targetPriorities, Collection $damageTypes)
    {
        $hero->loadMissing(Hero::heroResourceRelations());
        $fantasyPower = $this->getFantasyPower($hero);
        $combatAttacks = collect();
        $hero->items->each(function (Item $item) use ($hero, $fantasyPower, &$combatAttacks, $combatPositions, $targetPriorities, $damageTypes) {
            $combatAttacks = $combatAttacks->merge($item->attacks->map(function (Attack $attack) use ($hero, $item, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes) {
                return $this->createHeroCombatAttack($attack, $item, $hero, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes);
            }));
        });
    }

    protected function getFantasyPower(Hero $hero)
    {
        $totalPoints = $hero->playerSpirit->playerGameLog->playerStats->totalPoints();
        return FantasyPower::calculate($totalPoints);
    }

    public function createHeroCombatAttack(Attack $attack, Item $item, Hero $hero, float $fantasyPower, Collection $combatPositions, Collection $targetPriorities, Collection $damageTypes)
    {
        $attackerPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($attack) {
            return $combatPosition->id === $attack->attacker_position_id;
        });
        $targetPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($attack) {
            return $combatPosition->id === $attack->target_position_id;
        });
        $targetPriority = $targetPriorities->first(function (TargetPriority $targetPriority) use ($attack) {
            return $targetPriority->id === $attack->target_priority_id;
        });
        $damageType = $damageTypes->first(function (DamageType $damageType) use ($attack) {
            return $damageType->id === $attack->damage_type_id;
        });
        $damage = $this->calculateAttackDamage($attack, $fantasyPower);
        return new HeroCombatAttack(
            $hero->id,
            $item->id,
            $attack->id,
            $damage,
            $attack->getGrade(),
            $attack->getCombatSpeed(),
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $attack->getResourceCostCollection(),
        );
    }

    protected function calculateAttackDamage(Attack $attack, float $fantasyPower)
    {
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) min(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 0);
    }
}
