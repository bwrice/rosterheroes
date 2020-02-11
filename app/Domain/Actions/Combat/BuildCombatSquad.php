<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Combat\CombatHero;
use App\Domain\Combat\CombatSquad;
use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
use App\Domain\Models\TargetPriority;
use App\Exceptions\BuildCombatSquadException;
use App\Facades\FantasyPower;
use App\Facades\HeroService;
use Illuminate\Support\Collection;

class BuildCombatSquad
{
    /**
     * @param Squad $squad
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return CombatSquad
     */
    public function execute(Squad $squad, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null): CombatSquad
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $heroes = $squad->heroes->filter(function (Hero $hero) {
            return HeroService::combatReady($hero);
        });
        if ($heroes->isEmpty()) {
            throw new BuildCombatSquadException($squad, 'No heroes combat ready', BuildCombatSquadException::CODE_NO_COMBAT_READY_HEROES);
        }
        $combatHeroes = new CombatantCollection();
        $heroes->each(function (Hero $hero) use ($combatHeroes, $combatPositions, $targetPriorities, $damageTypes) {
            $combatHeroes->push($this->buildCombatHero($hero, $combatPositions, $targetPriorities, $damageTypes));
        });
        return new CombatSquad($squad->id, $squad->experience, $combatHeroes);
    }


    protected function buildCombatHero(Hero $hero, Collection $combatPositions, Collection $targetPriorities, Collection $damageTypes)
    {
        $hero->loadMissing(Hero::heroResourceRelations());
        $fantasyPower = $this->getFantasyPower($hero);
        $combatAttacks = collect();
        $hero->items->each(function (Item $item) use ($hero, $fantasyPower, &$combatAttacks, $combatPositions, $targetPriorities, $damageTypes) {
            $combatAttacks = $combatAttacks->merge($item->attacks->map(function (Attack $attack) use ($hero, $item, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes) {
                return $this->createHeroCombatAttack($attack, $item, $hero, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes);
            }));
        });

        $heroCombatPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($hero) {
            return $combatPosition->id === $hero->combat_position_id;
        });

        return new CombatHero(
            $hero->id,
            $hero->getCurrentMeasurableAmount(MeasurableType::HEALTH),
            $hero->getCurrentMeasurableAmount(MeasurableType::STAMINA),
            $hero->getCurrentMeasurableAmount(MeasurableType::MANA),
            $hero->getProtection(),
            $hero->getBlockChance(),
            $heroCombatPosition,
            $combatAttacks
        );
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
            $attack->name,
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
            $attack->getMaxTargetsCount()
        );
    }

    protected function calculateAttackDamage(Attack $attack, float $fantasyPower)
    {
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) max(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 1);
    }
}
