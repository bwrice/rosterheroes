<?php

namespace Tests\Unit;

use App\Domain\Behaviors\ItemBases\Weapons\AxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\BowBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\CrossbowBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\DaggerBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\MaceBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\OrbBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PoleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicOneHandBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicTwoHandBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\StaffBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\SwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ThrowingWeaponBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandAxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandSwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WandBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\MeasurableType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeaponBehaviorUnitTest extends TestCase
{
    use DatabaseTransactions;


    /** @var UsesItems */
    protected $usesItems;

    public function setUp(): void
    {
        parent::setUp();
        $this->usesItems = new class() implements UsesItems {

            protected $measurables = [];

            public function setMeasurable(string $measurableTypeName, int $value)
            {
                $this->measurables[$measurableTypeName] = $value;
            }

            public function getMeasurableAmount(string $measurableTypeName): int
            {
                if (array_key_exists($measurableTypeName, $this->measurables)) {
                    return $this->measurables[$measurableTypeName];
                }
                return 0;
            }
        };
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_strength
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_strength($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::STRENGTH, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::STRENGTH, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_strength()
    {
        return [
            ItemBase::AXE => [
                'weaponBehaviorClass' => AxeBehavior::class
            ],
            ItemBase::MACE => [
                'weaponBehaviorClass' => MaceBehavior::class
            ],
            ItemBase::SWORD => [
                'weaponBehaviorClass' => SwordBehavior::class
            ],
            ItemBase::TWO_HAND_AXE => [
                'weaponBehaviorClass' => TwoHandAxeBehavior::class
            ],
            ItemBase::TWO_HAND_SWORD => [
                'weaponBehaviorClass' => TwoHandSwordBehavior::class
            ],
            ItemBase::BOW => [
                'weaponBehaviorClass' => BowBehavior::class
            ],
            ItemBase::THROWING_WEAPON => [
                'weaponBehaviorClass' => ThrowingWeaponBehavior::class
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'weaponBehaviorClass' => PsionicTwoHandBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_valor
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_valor($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::VALOR, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::VALOR, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_valor()
    {
        return [
            ItemBase::AXE => [
                'weaponBehaviorClass' => AxeBehavior::class
            ],
            ItemBase::MACE => [
                'weaponBehaviorClass' => MaceBehavior::class
            ],
            ItemBase::SWORD => [
                'weaponBehaviorClass' => SwordBehavior::class
            ],
            ItemBase::TWO_HAND_AXE => [
                'weaponBehaviorClass' => TwoHandAxeBehavior::class
            ],
            ItemBase::TWO_HAND_SWORD => [
                'weaponBehaviorClass' => TwoHandSwordBehavior::class
            ],
            ItemBase::POLE_ARM => [
                'weaponBehaviorClass' => PoleArmBehavior::class
            ],
            ItemBase::STAFF => [
                'weaponBehaviorClass' => StaffBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_agility
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_agility($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_agility()
    {
        return [
            ItemBase::DAGGER => [
                'weaponBehaviorClass' => DaggerBehavior::class
            ],
            ItemBase::SWORD => [
                'weaponBehaviorClass' => SwordBehavior::class
            ],
            ItemBase::BOW => [
                'weaponBehaviorClass' => BowBehavior::class
            ],
            ItemBase::POLE_ARM => [
                'weaponBehaviorClass' => PoleArmBehavior::class
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'weaponBehaviorClass' => PsionicOneHandBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_focus
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_focus($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::FOCUS, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::FOCUS, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_focus()
    {
        return [
            ItemBase::DAGGER => [
                'weaponBehaviorClass' => DaggerBehavior::class
            ],
            ItemBase::BOW => [
                'weaponBehaviorClass' => BowBehavior::class
            ],
            ItemBase::CROSSBOW => [
                'weaponBehaviorClass' => CrossbowBehavior::class
            ],
            ItemBase::POLE_ARM => [
                'weaponBehaviorClass' => PoleArmBehavior::class
            ],
            ItemBase::THROWING_WEAPON => [
                'weaponBehaviorClass' => ThrowingWeaponBehavior::class
            ],
            ItemBase::ORB => [
                'weaponBehaviorClass' => OrbBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_aptitude
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_aptitude($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::APTITUDE, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::APTITUDE, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_aptitude()
    {
        return [
            ItemBase::WAND => [
                'weaponBehaviorClass' => WandBehavior::class
            ],
            ItemBase::CROSSBOW => [
                'weaponBehaviorClass' => CrossbowBehavior::class
            ],
            ItemBase::ORB => [
                'weaponBehaviorClass' => OrbBehavior::class
            ],
            ItemBase::STAFF => [
                'weaponBehaviorClass' => StaffBehavior::class
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'weaponBehaviorClass' => PsionicOneHandBehavior::class
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'weaponBehaviorClass' => PsionicTwoHandBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_intelligence
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_intelligence($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::INTELLIGENCE, 10);
        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::INTELLIGENCE, 99);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($this->usesItems);

        $diff = $highValorBaseDamageModifier - $lowValorBaseDamageModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_intelligence()
    {
        return [
            ItemBase::WAND => [
                'weaponBehaviorClass' => WandBehavior::class
            ],
            ItemBase::ORB => [
                'weaponBehaviorClass' => OrbBehavior::class
            ],
            ItemBase::STAFF => [
                'weaponBehaviorClass' => StaffBehavior::class
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'weaponBehaviorClass' => PsionicOneHandBehavior::class
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'weaponBehaviorClass' => PsionicTwoHandBehavior::class
            ],
        ];
    }

    /**
     * @test
     * @param $weaponBehaviorClass
     * @dataProvider provides_higher_agility_increases_the_speed_of_weapons
     */
    public function higher_agility_increases_the_speed_of_weapons($weaponBehaviorClass)
    {

        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 10);
        $lowAgilityModifier = $weaponBehavior->getCombatSpeedModifier($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 99);
        $higherAgilityModifier = $weaponBehavior->getCombatSpeedModifier($this->usesItems);

        $diff = $higherAgilityModifier - $lowAgilityModifier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_higher_agility_increases_the_speed_of_weapons()
    {
        return [
            ItemBase::WAND => [
                'weaponBehaviorClass' => WandBehavior::class
            ],
            ItemBase::ORB => [
                'weaponBehaviorClass' => OrbBehavior::class
            ],
            ItemBase::STAFF => [
                'weaponBehaviorClass' => StaffBehavior::class
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'weaponBehaviorClass' => PsionicOneHandBehavior::class
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'weaponBehaviorClass' => PsionicTwoHandBehavior::class
            ],
            ItemBase::CROSSBOW => [
                'weaponBehaviorClass' => CrossbowBehavior::class
            ],
            ItemBase::AXE => [
                'weaponBehaviorClass' => AxeBehavior::class
            ],
            ItemBase::MACE => [
                'weaponBehaviorClass' => MaceBehavior::class
            ],
            ItemBase::SWORD => [
                'weaponBehaviorClass' => SwordBehavior::class
            ],
            ItemBase::TWO_HAND_AXE => [
                'weaponBehaviorClass' => TwoHandAxeBehavior::class
            ],
            ItemBase::TWO_HAND_SWORD => [
                'weaponBehaviorClass' => TwoHandSwordBehavior::class
            ],
            ItemBase::POLE_ARM => [
                'weaponBehaviorClass' => PoleArmBehavior::class
            ],
            ItemBase::DAGGER => [
                'weaponBehaviorClass' => DaggerBehavior::class
            ],
            ItemBase::BOW => [
                'weaponBehaviorClass' => BowBehavior::class
            ],
            ItemBase::THROWING_WEAPON => [
                'weaponBehaviorClass' => ThrowingWeaponBehavior::class
            ],
        ];
    }
}
