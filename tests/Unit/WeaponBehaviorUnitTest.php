<?php

namespace Tests\Unit;

use App\Domain\Behaviors\ItemBases\Weapons\AxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\BowBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\DaggerBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\MaceBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PoleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\SwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ThrowingWeaponBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandAxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandSwordBehavior;
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
            ItemBase::MACE => [
                'weaponBehaviorClass' => DaggerBehavior::class
            ],
            ItemBase::SWORD => [
                'weaponBehaviorClass' => SwordBehavior::class
            ],
            ItemBase::TWO_HAND_AXE => [
                'weaponBehaviorClass' => BowBehavior::class
            ],
            ItemBase::TWO_HAND_SWORD => [
                'weaponBehaviorClass' => PoleArmBehavior::class
            ],
        ];
    }
}
