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
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\MeasurableType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeaponAttackUnitTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Attack */
    protected $attack;

    /** @var Hero */
    protected $hero;

    /** @var Item */
    protected $item;

    /** @var UsesItems */
    protected $usesItems;

    public function setUp(): void
    {
        parent::setUp();

        $this->attack = factory(Attack::class)->create();
        $this->hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $anySlot = $this->hero->slots->random();
        $this->item = factory(Item::class)->create();
        $this->item->slots()->save($anySlot);

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
     * @dataProvider provides_weapon_base_damage_is_increased_by_specific_measurables
     * @param $itemBaseName
     * @param $measurableTypeNames
     */
    public function weapon_base_damage_is_increased_by_specific_measurables($itemBaseName, $measurableTypeNames)
    {
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();

        foreach($measurableTypeNames as $measurableTypeName) {

            $measurable = $this->hero->getMeasurable($measurableTypeName);
            $measurable->amount_raised = 0;
            $measurable->save();

            $lowMeasurableBaseDamage = $this->attack->getBaseDamage($this->item->fresh());

            $measurable->amount_raised = 99;
            $measurable->save();

            $higherMeasurableBaseDamage = $this->attack->getBaseDamage($this->item->fresh());

            $diff = $higherMeasurableBaseDamage - $lowMeasurableBaseDamage;
            // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
            $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
        }
    }

    public function provides_weapon_base_damage_is_increased_by_specific_measurables()
    {
        return [
            ItemBase::DAGGER => [
                'itemBaseName' => ItemBase::DAGGER,
                'measurableTypeNames' => [
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::SWORD => [
                'itemBaseName' => ItemBase::SWORD,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY,
                ]
            ],
            ItemBase::AXE => [
                'itemBaseName' => ItemBase::AXE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::MACE => [
                'itemBaseName' => ItemBase::MACE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::BOW => [
                'itemBaseName' => ItemBase::BOW,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::CROSSBOW => [
                'itemBaseName' => ItemBase::CROSSBOW,
                'measurableTypeNames' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE,
                ]
            ],
            ItemBase::THROWING_WEAPON => [
                'itemBaseName' => ItemBase::THROWING_WEAPON,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::POLE_ARM => [
                'itemBaseName' => ItemBase::POLE_ARM,
                'measurableTypeNames' => [
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY,
                    MeasurableType::APTITUDE,
                ]
            ],
            ItemBase::TWO_HAND_SWORD => [
                'itemBaseName' => ItemBase::TWO_HAND_SWORD,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::TWO_HAND_AXE => [
                'itemBaseName' => ItemBase::TWO_HAND_AXE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::WAND => [
                'itemBaseName' => ItemBase::WAND,
                'measurableTypeNames' => [
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::ORB => [
                'itemBaseName' => ItemBase::ORB,
                'measurableTypeNames' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::STAFF => [
                'itemBaseName' => ItemBase::STAFF,
                'measurableTypeNames' => [
                    MeasurableType::VALOR,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_ONE_HAND,
                'measurableTypeNames' => [
                    MeasurableType::AGILITY,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_TWO_HAND,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_weapon_damage_multiplier_is_increased_by_specific_measurables
     * @param $itemBaseName
     * @param $measurableTypeNames
     */
    public function weapon_damage_multiplier_is_increased_by_specific_measurables($itemBaseName, $measurableTypeNames)
    {
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();

        foreach($measurableTypeNames as $measurableTypeName) {

            $measurable = $this->hero->getMeasurable($measurableTypeName);
            $measurable->amount_raised = 0;
            $measurable->save();

            $lowMeasurableBaseDamage = $this->attack->getDamageMultiplier($this->item->fresh());

            $measurable->amount_raised = 99;
            $measurable->save();

            $higherMeasurableBaseDamage = $this->attack->getDamageMultiplier($this->item->fresh());

            $diff = $higherMeasurableBaseDamage - $lowMeasurableBaseDamage;
            // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
            $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
        }
    }

    public function provides_weapon_damage_multiplier_is_increased_by_specific_measurables()
    {
        return [
            ItemBase::DAGGER => [
                'itemBaseName' => ItemBase::DAGGER,
                'measurableTypeNames' => [
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::SWORD => [
                'itemBaseName' => ItemBase::SWORD,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY,
                ]
            ],
            ItemBase::AXE => [
                'itemBaseName' => ItemBase::AXE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::MACE => [
                'itemBaseName' => ItemBase::MACE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::BOW => [
                'itemBaseName' => ItemBase::BOW,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::CROSSBOW => [
                'itemBaseName' => ItemBase::CROSSBOW,
                'measurableTypeNames' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE,
                ]
            ],
            ItemBase::THROWING_WEAPON => [
                'itemBaseName' => ItemBase::THROWING_WEAPON,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::FOCUS,
                ]
            ],
            ItemBase::POLE_ARM => [
                'itemBaseName' => ItemBase::POLE_ARM,
                'measurableTypeNames' => [
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY,
                    MeasurableType::APTITUDE,
                ]
            ],
            ItemBase::TWO_HAND_SWORD => [
                'itemBaseName' => ItemBase::TWO_HAND_SWORD,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::TWO_HAND_AXE => [
                'itemBaseName' => ItemBase::TWO_HAND_AXE,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                ]
            ],
            ItemBase::WAND => [
                'itemBaseName' => ItemBase::WAND,
                'measurableTypeNames' => [
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::ORB => [
                'itemBaseName' => ItemBase::ORB,
                'measurableTypeNames' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::STAFF => [
                'itemBaseName' => ItemBase::STAFF,
                'measurableTypeNames' => [
                    MeasurableType::VALOR,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_ONE_HAND,
                'measurableTypeNames' => [
                    MeasurableType::AGILITY,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_TWO_HAND,
                'measurableTypeNames' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE,
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_a_higher_damage_multiplier_with_more_valor
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_a_higher_damage_multiplier_with_more_valor($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::VALOR, 10);
        $lowValorDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $this->usesItems->setMeasurable(MeasurableType::VALOR, 99);
        $highValorDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $diff = $highValorDamageMultiplier - $lowValorDamageMultiplier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_a_higher_damage_multiplier_with_more_valor()
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
     * @dataProvider provides_certain_weapons_have_a_higher_damage_multiplier_with_more_agility
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_a_higher_damage_multiplier_with_more_agility($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 10);
        $lowAgilityDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 99);
        $highAgilityDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $diff = $highAgilityDamageMultiplier - $lowAgilityDamageMultiplier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_a_higher_damage_multiplier_with_more_agility()
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
     * @dataProvider provides_certain_weapons_have_a_higher_damage_multiplier_with_more_focus
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_a_higher_damage_multiplier_with_more_focus($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::FOCUS, 10);
        $lowFocusDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $this->usesItems->setMeasurable(MeasurableType::FOCUS, 99);
        $highFocusDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $diff = $highFocusDamageMultiplier - $lowFocusDamageMultiplier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_a_higher_damage_multiplier_with_more_focus()
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
     * @dataProvider provides_certain_weapons_have_a_higher_damage_multiplier_with_more_aptitude
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_a_higher_damage_multiplier_with_more_aptitude($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::APTITUDE, 10);
        $lowAptitudeDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $this->usesItems->setMeasurable(MeasurableType::APTITUDE, 99);
        $highAptitudeDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $diff = $highAptitudeDamageMultiplier - $lowAptitudeDamageMultiplier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_a_higher_damage_multiplier_with_more_aptitude()
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
     * @dataProvider provides_certain_weapons_have_a_higher_damage_multiplier_with_more_intelligence
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_a_higher_damage_multiplier_with_more_intelligence($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $this->usesItems->setMeasurable(MeasurableType::INTELLIGENCE, 10);
        $lowIntelligenceDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $this->usesItems->setMeasurable(MeasurableType::INTELLIGENCE, 99);
        $highIntelligenceDamageMultiplier = $weaponBehavior->getDamageMultiplierBonus($this->usesItems);

        $diff = $highIntelligenceDamageMultiplier - $lowIntelligenceDamageMultiplier;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_certain_weapons_have_a_higher_damage_multiplier_with_more_intelligence()
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
        $lowAgilityModifier = $weaponBehavior->getCombatSpeedBonus($this->usesItems);


        $this->usesItems->setMeasurable(MeasurableType::AGILITY, 99);
        $higherAgilityModifier = $weaponBehavior->getCombatSpeedBonus($this->usesItems);

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
