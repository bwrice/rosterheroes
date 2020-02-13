<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use App\Facades\FantasyPower;
use Illuminate\Database\Eloquent\Collection;

class BuildCombatHero
{
    /**
     * @var BuildHeroCombatAttack
     */
    private $buildHeroCombatAttack;

    public function __construct(BuildHeroCombatAttack $buildHeroCombatAttack)
    {
        $this->buildHeroCombatAttack = $buildHeroCombatAttack;
    }

    public function execute(Hero $hero, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();
        $hero->loadMissing(Hero::heroResourceRelations());
        $fantasyPower = $this->getFantasyPower($hero);
        $combatAttacks = collect();
        $hero->items->each(function (Item $item) use ($hero, $fantasyPower, &$combatAttacks, $combatPositions, $targetPriorities, $damageTypes) {
            $combatAttacks = $combatAttacks->merge($item->attacks->map(function (Attack $attack) use ($hero, $item, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes) {
                return $this->buildHeroCombatAttack->execute($attack, $item, $hero, $fantasyPower, $combatPositions, $targetPriorities, $damageTypes);
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
}
