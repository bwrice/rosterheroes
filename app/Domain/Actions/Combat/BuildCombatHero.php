<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BuildCombatHero
 * @package App\Domain\Actions\Combat
 * @deprecated
 */
class BuildCombatHero
{
    /**
     * @var BuildHeroCombatAttack
     */
    protected $buildHeroCombatAttack;
    /**
     * @var CalculateHeroFantasyPower
     */
    protected $calculateHeroFantasyPower;

    public function __construct(CalculateHeroFantasyPower $calculateHeroFantasyPower, BuildHeroCombatAttack $buildHeroCombatAttack)
    {
        $this->calculateHeroFantasyPower = $calculateHeroFantasyPower;
        $this->buildHeroCombatAttack = $buildHeroCombatAttack;
    }

    public function execute(Hero $hero, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();
        $hero->loadMissing(Hero::heroResourceRelations());
        $heroFantasyPower = $this->calculateHeroFantasyPower->execute($hero);
        $combatAttacks = new CombatAttackCollection();
        $hero->items->each(function (Item $item) use ($hero, $heroFantasyPower, &$combatAttacks, $combatPositions, $targetPriorities, $damageTypes) {
            $combatAttacks = $combatAttacks->merge($item->getAttacks()->map(function (Attack $attack) use ($hero, $item, $heroFantasyPower, $combatPositions, $targetPriorities, $damageTypes) {
                return $this->buildHeroCombatAttack->execute($attack, $item, $hero, $heroFantasyPower, $combatPositions, $targetPriorities, $damageTypes);
            }));
        });

        $heroCombatPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($hero) {
            return $combatPosition->id === $hero->combat_position_id;
        });

        return new CombatHero(
            $hero->uuid,
            $hero->getCurrentMeasurableAmount(MeasurableType::HEALTH),
            $hero->getCurrentMeasurableAmount(MeasurableType::STAMINA),
            $hero->getCurrentMeasurableAmount(MeasurableType::MANA),
            $hero->getProtection(),
            $hero->getBlockChance(),
            $heroCombatPosition,
            $combatAttacks,
            $hero->playerSpirit->uuid
        );
    }
}
