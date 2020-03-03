<?php


namespace App\Factories\Combat;


use App\Domain\Behaviors\HeroClasses\HeroClassBehavior;
use App\Domain\Behaviors\HeroClasses\RangerBehavior;
use App\Domain\Behaviors\HeroClasses\WarriorBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\HealthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ManaBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\StaminaBehavior;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use App\Factories\Models\HeroFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatHeroFactory extends AbstractCombatantFactory
{
    /** @var string|null */
    protected $heroUuid;

    /** @var HeroFactory */
    protected $heroFactory;

    protected $health;

    protected $stamina;

    protected $mana;

    protected $protection;

    protected $blockChancePercent;

    /** @var Collection|null */
    protected $heroCombatAttackFactories;

    public function create()
    {
        $combatPosition = $this->getCombatPosition();
        $heroUuid = $this->getHeroUuid();
        $heroCombatAttacks = $this->getHeroCombatAttacks($heroUuid);

        return new CombatHero(
            $heroUuid,
            is_null($this->health) ? 800 : $this->health,
            is_null($this->stamina) ? 500 : $this->stamina,
            is_null($this->mana) ? 400 : $this->mana,
            is_null($this->protection) ? 100 : $this->protection,
            is_null($this->blockChancePercent) ? 10: $this->blockChancePercent,
            $combatPosition,
            $heroCombatAttacks
        );
    }

    public function forHero(string $heroUuid)
    {
        $clone = clone $this;
        $clone->heroUuid = $heroUuid;
        return $clone;
    }

    public function withMana(int $mana)
    {
        $clone = clone $this;
        $clone->mana = $mana;
        return $clone;
    }

    public function withStamina(int $stamina)
    {
        $clone = clone $this;
        $clone->stamina = $stamina;
        return $clone;
    }

    public function withHealth(int $health)
    {
        $clone = clone $this;
        $clone->health = $health;
        return $clone;
    }

    public function withProtection(int $protection)
    {
        $clone = clone $this;
        $clone->protection = $protection;
        return $clone;
    }

    public function withBlockChancePercent(float $blockChancePercent)
    {
        $clone = clone $this;
        $clone->blockChancePercent = $blockChancePercent;
        return $clone;
    }

    public function fromHeroFactory(HeroFactory $heroFactory)
    {
        $clone = clone $this;
        $clone->heroFactory = $heroFactory;
        return $clone;
    }

    public function withHeroCombatAttacks(Collection $heroCombatAttackFactories)
    {
        $clone = clone $this;
        $clone->heroCombatAttackFactories = $heroCombatAttackFactories;
        return $clone;
    }

    protected function getHeroUuid()
    {
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->create()->uuid;
    }

    protected function getNoobFactoryWithResources(HeroClassBehavior $heroClassBehavior)
    {
        $clone = clone $this;
        /** @var HealthBehavior $healthBehavior */
        $healthBehavior = app(HealthBehavior::class);
        $health = $heroClassBehavior->getMeasurableStartingAmount($healthBehavior);
        /** @var StaminaBehavior $staminaBehavior */
        $staminaBehavior = app(StaminaBehavior::class);
        $stamina = $heroClassBehavior->getMeasurableStartingAmount($staminaBehavior);
        /** @var ManaBehavior $manaBehavior */
        $manaBehavior = app(ManaBehavior::class);
        $mana = $heroClassBehavior->getMeasurableStartingAmount($manaBehavior);

        return $clone->withHealth($health)
            ->withStamina($stamina)
            ->withMana($mana);
    }

    public function noobWarrior()
    {
        $clone = $this->getNoobFactoryWithResources(app(WarriorBehavior::class));

        $clone = $clone->withProtection(200)
            ->withBlockChancePercent(10)
            ->withCombatPosition(CombatPosition::FRONT_LINE);

        $baseCombatAttackFactory = CombatAttackFactory::new()
            ->withAttackerPosition(CombatPosition::FRONT_LINE)
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withDamageType(DamageType::FIXED_TARGET)
            ->withTargetPriority(TargetPriority::ANY);

        $meleeAttackOne = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(40)
                    ->withCombatSpeed(40)
                    ->withMaxTargetCount(1)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 8)
            ]));

        $meleeAttackTwo = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(50)
                    ->withCombatSpeed(18)
                    ->withMaxTargetCount(2)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 15),
                new FixedResourceCost(MeasurableType::MANA, 3)
            ]));

        $meleeAttackThree = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(75)
                    ->withCombatSpeed(10)
                    ->withMaxTargetCount(3)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 25),
                new FixedResourceCost(MeasurableType::MANA, 8)
            ]));

        $clone = $clone->withHeroCombatAttacks(collect([
            $meleeAttackOne,
            $meleeAttackTwo,
            $meleeAttackThree
        ]));

        return $clone;
    }

    public function noobRanger()
    {
        $clone = $this->getNoobFactoryWithResources(app(RangerBehavior::class));

        $clone = $clone->withProtection(50)
            ->withBlockChancePercent(3)
            ->withCombatPosition(CombatPosition::BACK_LINE);

        $baseCombatAttackFactory = CombatAttackFactory::new()
            ->withAttackerPosition(CombatPosition::BACK_LINE)
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withDamageType(DamageType::FIXED_TARGET)
            ->withTargetPriority(TargetPriority::ANY);

        $rangedAttackOne = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(60)
                    ->withCombatSpeed(35)
                    ->withMaxTargetCount(1)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 16)
            ]));

        $rangedAttackTwo = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(85)
                    ->withCombatSpeed(15)
                    ->withMaxTargetCount(2)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 28),
                new FixedResourceCost(MeasurableType::MANA, 8)
            ]));

        $rangedAttackThree = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withDamage(100)
                    ->withCombatSpeed(7)
                    ->withMaxTargetCount(3)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 40),
                new FixedResourceCost(MeasurableType::MANA, 12)
            ]));

        $meleeAttack = HeroCombatAttackFactory::new()
            ->withCombatAttackFactory(
                $baseCombatAttackFactory
                    ->withAttackerPosition(CombatPosition::FRONT_LINE)
                    ->withDamage(30)
                    ->withCombatSpeed(25)
                    ->withMaxTargetCount(1)
            )->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 10)
            ]));

        $clone = $clone->withHeroCombatAttacks(collect([
            $rangedAttackOne,
            $rangedAttackTwo,
            $rangedAttackThree,
            $meleeAttack
        ]));

        return $clone;
    }

    protected function getHeroCombatAttacks(string $heroUuid)
    {
        if ($this->heroCombatAttackFactories) {
            return $this->heroCombatAttackFactories->map(function (HeroCombatAttackFactory $factory) use ($heroUuid) {
                return $factory->forHero($heroUuid)->create();
            });
        }
        return collect();
    }
}
