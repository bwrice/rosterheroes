<?php


namespace App\Factories\Combat;


use App\Domain\Behaviors\HeroClasses\HeroClassBehavior;
use App\Domain\Behaviors\HeroClasses\RangerBehavior;
use App\Domain\Behaviors\HeroClasses\SorcererBehavior;
use App\Domain\Behaviors\HeroClasses\WarriorBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\HealthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ManaBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\StaminaBehavior;
use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
use App\Domain\Models\TargetPriority;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
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

    /** @var Squad|null */
    protected $squad;

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
            $heroCombatAttacks,
            PlayerSpiritFactory::new()->create()->uuid
        );
    }

    public function forHero(string $heroUuid)
    {
        $clone = clone $this;
        $clone->heroUuid = $heroUuid;
        return $clone;
    }

    public function forSquad(Squad $squad)
    {
        $clone = clone $this;
        $clone->squad = $squad;
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
        if ($this->heroUuid) {
            return $this->heroUuid;
        }
        if ($this->heroFactory) {
            $heroFactory = $this->heroFactory;
        } else {
            $heroFactory = HeroFactory::new();
            if ($this->squad) {
                $heroFactory = $heroFactory->forSquad($this->squad);
            }
        }
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

        $clone = $clone->withProtection(500)
            ->withBlockChancePercent(7.5)
            ->withCombatPosition(CombatPosition::FRONT_LINE);

        $baseCombatAttackFactory = HeroCombatAttackFactory::new()
            ->withAttackerPosition(CombatPosition::FRONT_LINE)
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withDamageType(DamageType::FIXED_TARGET)
            ->withTargetPriority(TargetPriority::ANY);

        $meleeAttackOne = $baseCombatAttackFactory
            ->withDamage(300)
            ->withCombatSpeed(25)
            ->withMaxTargetCount(1)
            ->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 8)
            ]));

//        $meleeAttackTwo = $baseCombatAttackFactory
//            ->withDamage(50)
//            ->withCombatSpeed(18)
//            ->withMaxTargetCount(2)
//            ->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 15),
//                new FixedResourceCost(MeasurableType::MANA, 3)
//            ]));
//
//        $meleeAttackThree = $baseCombatAttackFactory
//            ->withDamage(75)
//            ->withCombatSpeed(10)
//            ->withMaxTargetCount(3)
//            ->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 25),
//                new FixedResourceCost(MeasurableType::MANA, 8)
//            ]));

        $clone = $clone->withHeroCombatAttacks(collect([
            $meleeAttackOne,
//            $meleeAttackTwo,
//            $meleeAttackThree
        ]));

        return $clone;
    }

    public function noobRanger()
    {
        $clone = $this->getNoobFactoryWithResources(app(RangerBehavior::class));

        $clone = $clone->withProtection(100)
            ->withBlockChancePercent(1.5)
            ->withCombatPosition(CombatPosition::BACK_LINE);

        $baseCombatAttackFactory = HeroCombatAttackFactory::new()
            ->withAttackerPosition(CombatPosition::BACK_LINE)
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withDamageType(DamageType::FIXED_TARGET)
            ->withTargetPriority(TargetPriority::ANY);

        $rangedAttackOne = $baseCombatAttackFactory
            ->withDamage(320)
            ->withCombatSpeed(16)
            ->withMaxTargetCount(1)
            ->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 16)
            ]));

//        $rangedAttackTwo = $baseCombatAttackFactory
//            ->withDamage(85)
//            ->withCombatSpeed(15)
//            ->withMaxTargetCount(2)
//            ->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 28),
//                new FixedResourceCost(MeasurableType::MANA, 8)
//            ]));
//
//        $rangedAttackThree = $baseCombatAttackFactory
//            ->withDamage(100)
//            ->withCombatSpeed(7)
//            ->withMaxTargetCount(3)
//            ->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 40),
//                new FixedResourceCost(MeasurableType::MANA, 12)
//            ]));
//
//        $meleeAttack = $baseCombatAttackFactory
//            ->withAttackerPosition(CombatPosition::FRONT_LINE)
//            ->withDamage(30)
//            ->withCombatSpeed(25)
//            ->withMaxTargetCount(1)
//            ->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 10)
//            ]));

        $clone = $clone->withHeroCombatAttacks(collect([
            $rangedAttackOne,
//            $rangedAttackTwo,
//            $rangedAttackThree,
//            $meleeAttack
        ]));

        return $clone;
    }

    public function noobSorcerer()
    {
        $clone = $this->getNoobFactoryWithResources(app(SorcererBehavior::class));

        $clone = $clone->withProtection(50)
            ->withBlockChancePercent(1)
            ->withCombatPosition(CombatPosition::BACK_LINE);

        $baseCombatAttackFactory = HeroCombatAttackFactory::new()
            ->withAttackerPosition(CombatPosition::BACK_LINE)
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withDamageType(DamageType::FIXED_TARGET)
            ->withTargetPriority(TargetPriority::ANY);

        $rangedAttackOne = $baseCombatAttackFactory
            ->withDamage(300)
            ->withCombatSpeed(16)
            ->withMaxTargetCount(1)->withResourceCosts(new ResourceCostsCollection([
                new FixedResourceCost(MeasurableType::STAMINA, 5),
                new FixedResourceCost(MeasurableType::MANA, 14)
            ]));

//        $rangedAttackTwo = $baseCombatAttackFactory
//            ->withDamage(55)
//            ->withCombatSpeed(25)
//            ->withMaxTargetCount(2)->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 10),
//                new FixedResourceCost(MeasurableType::MANA, 25)
//            ]));
//
//        $rangedAttackThree = $baseCombatAttackFactory
//            ->withDamage(70)
//            ->withCombatSpeed(16)
//            ->withMaxTargetCount(3)->withResourceCosts(new ResourceCostsCollection([
//                new FixedResourceCost(MeasurableType::STAMINA, 14),
//                new FixedResourceCost(MeasurableType::MANA, 30)
//            ]));

        $clone = $clone->withHeroCombatAttacks(collect([
            $rangedAttackOne,
//            $rangedAttackTwo,
//            $rangedAttackThree,
        ]));

        return $clone;
    }

    protected function getHeroCombatAttacks(string $heroUuid)
    {
        $combatAttacks = [];
        if ($this->heroCombatAttackFactories) {
            $combatAttacks = $this->heroCombatAttackFactories->map(function (HeroCombatAttackFactory $factory) use ($heroUuid) {
                return $factory->forHero($heroUuid)->create();
            });
        }
        return new CombatAttackCollection($combatAttacks);
    }
}
