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
     * @dataProvider provides_weapon_speed_is_increased_by_agility
     * @param $itemBaseName
     */
    public function weapon_speed_is_increased_by_agility($itemBaseName)
    {
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();

        $measurable = $this->hero->getMeasurable(MeasurableType::AGILITY);
        $measurable->amount_raised = 0;
        $measurable->save();

        $lowMeasurableBaseDamage = $this->attack->getCombatSpeed($this->item->fresh());

        $measurable->amount_raised = 99;
        $measurable->save();

        $higherMeasurableBaseDamage = $this->attack->getCombatSpeed($this->item->fresh());

        $diff = $higherMeasurableBaseDamage - $lowMeasurableBaseDamage;
        // Make sure the diff is greater than PHP float error, AKA, a number very close to zero
        $this->assertGreaterThan(PHP_FLOAT_EPSILON, $diff);
    }

    public function provides_weapon_speed_is_increased_by_agility()
    {

        return [
            ItemBase::DAGGER => [
                'itemBaseName' => ItemBase::DAGGER,
            ],
            ItemBase::SWORD => [
                'itemBaseName' => ItemBase::SWORD,
            ],
            ItemBase::AXE => [
                'itemBaseName' => ItemBase::AXE,
            ],
            ItemBase::MACE => [
                'itemBaseName' => ItemBase::MACE,
            ],
            ItemBase::BOW => [
                'itemBaseName' => ItemBase::BOW,
            ],
            ItemBase::CROSSBOW => [
                'itemBaseName' => ItemBase::CROSSBOW,
            ],
            ItemBase::THROWING_WEAPON => [
                'itemBaseName' => ItemBase::THROWING_WEAPON,
            ],
            ItemBase::POLE_ARM => [
                'itemBaseName' => ItemBase::POLE_ARM,
            ],
            ItemBase::TWO_HAND_SWORD => [
                'itemBaseName' => ItemBase::TWO_HAND_SWORD,
            ],
            ItemBase::TWO_HAND_AXE => [
                'itemBaseName' => ItemBase::TWO_HAND_AXE,
            ],
            ItemBase::WAND => [
                'itemBaseName' => ItemBase::WAND,
            ],
            ItemBase::ORB => [
                'itemBaseName' => ItemBase::ORB,
            ],
            ItemBase::STAFF => [
                'itemBaseName' => ItemBase::STAFF,
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_ONE_HAND,
            ],
            ItemBase::PSIONIC_TWO_HAND => [
                'itemBaseName' => ItemBase::PSIONIC_TWO_HAND,
            ]
        ];
    }
}
