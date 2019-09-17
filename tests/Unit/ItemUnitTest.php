<?php

namespace Tests\Unit;

use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\MaterialType;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemUnitTest extends TestCase
{
    /** @var Item */
    protected $item;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();
        $this->item = factory(Item::class)->create();
        $this->hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $anySlot = $this->hero->slots->random();
        $this->item = factory(Item::class)->create();
        $this->item->slots()->save($anySlot);
    }

    /**
     * @test
     */
    public function a_higher_grade_item_type_of_the_same_base_and_material_weighs_more()
    {
        $itemBaseID = ItemBase::query()->inRandomOrder()->first()->id;
        $itemTypes = ItemType::query()->where('item_base_id', '=', $itemBaseID)->orderBy('grade')->get();
        $this->assertGreaterThan(1, $itemTypes->count());

        /** @var ItemType $type1 */
        $type1 = $itemTypes->shift();
        /** @var ItemType $type2 */
        $type2 = $itemTypes->shift();

        $this->assertGreaterThan($type1->grade, $type2->grade);

        $this->item->item_type_id = $type1->id;
        $this->item->save();
        $weight1 = $this->item->fresh()->getWeight();

        $this->item->item_type_id = $type2->id;
        $this->item->save();
        $weight2 = $this->item->fresh()->getWeight();

        $this->assertGreaterThan($weight1, $weight2);
    }

    /**
     * @test
     */
    public function an_item_with_a_higher_grade_material_of_the_same_material_type_for_the_item_type_weighs_more()
    {
        $materialTypeID = MaterialType::query()->inRandomOrder()->first()->id;
        $materials = Material::query()->where('material_type_id', '=', $materialTypeID)->orderBy('grade')->get();
        $this->assertGreaterThan(1, $materials->count());

        /** @var Material $material1 */
        $material1 = $materials->shift();
        /** @var Material $material2 */
        $material2 = $materials->shift();

        $this->assertGreaterThan($material1->grade, $material2->grade);

        $this->item->material_id = $material1->id;
        $this->item->save();
        $weight1 = $this->item->fresh()->getWeight();

        $this->item->material_id = $material2->id;
        $this->item->save();
        $weight2 = $this->item->fresh()->getWeight();

        $this->assertGreaterThan($weight1, $weight2);
    }

    /**
     * @test
     * @dataProvider provides_items_of_certain_material_types_weigh_more
     * @param $materialType1
     * @param $materialType2
     */
    public function items_of_certain_material_types_weigh_more($materialType1, $materialType2)
    {
        $materialType1_ID = MaterialType::forName($materialType1)->id;
        $materialType2_ID = MaterialType::forName($materialType2)->id;

        /** @var Material $firstMaterial */
        $firstMaterial = Material::query()->where('material_type_id', '=', $materialType1_ID)->orderBy('grade')->first();
        /** @var Material $secondMaterial */
        $secondMaterial = Material::query()->where('material_type_id', '=', $materialType2_ID)->orderBy('grade')->first();
        /** @var Material $secondMaterial */

        $this->assertNotNull($secondMaterial);

        $this->item->material_id = $firstMaterial->id;
        $this->item->save();
        $weight1 = $this->item->fresh()->getWeight();

        $this->item->material_id = $secondMaterial->id;
        $this->item->save();
        $weight2 = $this->item->fresh()->getWeight();

        $this->assertGreaterThan($weight1, $weight2);
    }

    public function provides_items_of_certain_material_types_weigh_more()
    {
        return [
            MaterialType::CLOTH . ' vs ' . MaterialType::HIDE => [
                'materialType1' => MaterialType::CLOTH,
                'materialType2' => MaterialType::HIDE
            ],
            MaterialType::PSIONIC . ' vs ' . MaterialType::BONE => [
                'materialType1' => MaterialType::PSIONIC,
                'materialType2' => MaterialType::BONE
            ],
            MaterialType::HIDE . ' vs ' . MaterialType::BONE => [
                'materialType1' => MaterialType::HIDE,
                'materialType2' => MaterialType::BONE
            ],
            MaterialType::PSIONIC . ' vs ' . MaterialType::BONE => [
                'materialType1' => MaterialType::PSIONIC,
                'materialType2' => MaterialType::BONE
            ],
            MaterialType::BONE . ' vs ' . MaterialType::METAL => [
                'materialType1' => MaterialType::BONE,
                'materialType2' => MaterialType::METAL
            ],
            MaterialType::WOOD . ' vs ' . MaterialType::METAL => [
                'materialType1' => MaterialType::WOOD,
                'materialType2' => MaterialType::METAL
            ],
            MaterialType::WOOD . ' vs ' . MaterialType::BONE => [
                'materialType1' => MaterialType::WOOD,
                'materialType2' => MaterialType::BONE
            ],
            MaterialType::METAL . ' vs ' . MaterialType::GEMSTONE => [
                'materialType1' => MaterialType::METAL,
                'materialType2' => MaterialType::GEMSTONE
            ],
            MaterialType::WOOD . ' vs ' . MaterialType::PRECIOUS_METAL => [
                'materialType1' => MaterialType::WOOD,
                'materialType2' => MaterialType::PRECIOUS_METAL
            ]
        ];
    }

    /**
     * @test
     * @dataProvider provides_items_of_a_certain_item_base_weigh_more
     * @param $lighterBase
     * @param $heavierBase
     */
    public function items_of_a_certain_item_base_weigh_more($lighterBase, $heavierBase)
    {
        $lighterBase_id = ItemBase::forName($lighterBase)->id;
        $heavierBase_id = ItemBase::forName($heavierBase)->id;

        /** @var ItemType $heavierItemType */
        $heavierItemType = ItemType::query()->where('item_base_id', '=', $heavierBase_id)->inRandomOrder()->first();

        /** @var ItemType $lighterItemType */
        $lighterItemType = ItemType::query()->where('item_base_id', '=', $lighterBase_id)->inRandomOrder()->first();

        $this->assertNotNull($lighterItemType);

        $this->item->item_type_id = $lighterItemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();
        // Set to same grade to compare
        $this->item->itemType->grade = 10;
        $lighterItemTypeWeight = $this->item->getWeight();

        $this->item->item_type_id = $heavierItemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();
        $this->item->itemType->grade = 10;
        $heavierItemTypeWeight = $this->item->getWeight();

        $this->assertGreaterThan($lighterItemTypeWeight, $heavierItemTypeWeight);
    }

    public function provides_items_of_a_certain_item_base_weigh_more()
    {
        return [
            ItemBase::DAGGER . ' vs ' . ItemBase::SWORD => [
                'lighterBase' => ItemBase::DAGGER,
                'heavierBase' => ItemBase::SWORD
            ],
            ItemBase::SWORD . ' vs ' . ItemBase::MACE => [
                'lighterBase' => ItemBase::SWORD,
                'heavierBase' => ItemBase::MACE
            ],
            ItemBase::AXE . ' vs ' . ItemBase::TWO_HAND_SWORD => [
                'lighterBase' => ItemBase::AXE,
                'heavierBase' => ItemBase::TWO_HAND_SWORD
            ],
            ItemBase::MACE . ' vs ' . ItemBase::TWO_HAND_AXE => [
                'lighterBase' => ItemBase::MACE,
                'heavierBase' => ItemBase::TWO_HAND_AXE
            ],
            ItemBase::POLE_ARM . ' vs ' . ItemBase::TWO_HAND_SWORD => [
                'lighterBase' => ItemBase::POLE_ARM,
                'heavierBase' => ItemBase::TWO_HAND_SWORD
            ],
            ItemBase::BOW . ' vs ' . ItemBase::CROSSBOW => [
                'lighterBase' => ItemBase::BOW,
                'heavierBase' => ItemBase::CROSSBOW
            ],
            ItemBase::WAND . ' vs ' . ItemBase::ORB => [
                'lighterBase' => ItemBase::WAND,
                'heavierBase' => ItemBase::ORB
            ],
            ItemBase::ORB . ' vs ' . ItemBase::STAFF => [
                'lighterBase' => ItemBase::ORB,
                'heavierBase' => ItemBase::STAFF
            ],
            ItemBase::PSIONIC_ONE_HAND . ' vs ' . ItemBase::PSIONIC_TWO_HAND => [
                'lighterBase' => ItemBase::PSIONIC_ONE_HAND,
                'heavierBase' => ItemBase::PSIONIC_TWO_HAND
            ],
            ItemBase::PSIONIC_TWO_HAND . ' vs ' . ItemBase::TWO_HAND_SWORD => [
                'lighterBase' => ItemBase::PSIONIC_TWO_HAND,
                'heavierBase' => ItemBase::TWO_HAND_SWORD
            ],
            ItemBase::STAFF . ' vs ' . ItemBase::BOW => [
                'lighterBase' => ItemBase::STAFF,
                'heavierBase' => ItemBase::BOW
            ],
            ItemBase::DAGGER . ' vs ' . ItemBase::THROWING_WEAPON => [
                'lighterBase' => ItemBase::DAGGER,
                'heavierBase' => ItemBase::THROWING_WEAPON
            ],
            ItemBase::THROWING_WEAPON . ' vs ' . ItemBase::AXE => [
                'lighterBase' => ItemBase::THROWING_WEAPON,
                'heavierBase' => ItemBase::AXE
            ],
            ItemBase::CAP . ' vs ' . ItemBase::HELMET => [
                'lighterBase' => ItemBase::CAP,
                'heavierBase' => ItemBase::HELMET
            ],
            ItemBase::LIGHT_ARMOR . ' vs ' . ItemBase::HEAVY_ARMOR => [
                'lighterBase' => ItemBase::LIGHT_ARMOR,
                'heavierBase' => ItemBase::HEAVY_ARMOR
            ],
            ItemBase::ROBES . ' vs ' . ItemBase::LIGHT_ARMOR => [
                'lighterBase' => ItemBase::ROBES,
                'heavierBase' => ItemBase::LIGHT_ARMOR
            ],
            ItemBase::LEGGINGS . ' vs ' . ItemBase::HEAVY_ARMOR => [
                'lighterBase' => ItemBase::LEGGINGS,
                'heavierBase' => ItemBase::HEAVY_ARMOR
            ],
            ItemBase::CAP . ' vs ' . ItemBase::CROWN => [
                'lighterBase' => ItemBase::CAP,
                'heavierBase' => ItemBase::CROWN
            ],
            ItemBase::SASH . ' vs ' . ItemBase::BELT => [
                'lighterBase' => ItemBase::SASH,
                'heavierBase' => ItemBase::BELT
            ],
            ItemBase::SHOES . ' vs ' . ItemBase::BOOTS => [
                'lighterBase' => ItemBase::SHOES,
                'heavierBase' => ItemBase::BOOTS
            ],
            ItemBase::GLOVES . ' vs ' . ItemBase::GAUNTLETS => [
                'lighterBase' => ItemBase::GLOVES,
                'heavierBase' => ItemBase::GAUNTLETS
            ],
            ItemBase::PSIONIC_SHIELD . ' vs ' . ItemBase::SHIELD => [
                'lighterBase' => ItemBase::PSIONIC_SHIELD,
                'heavierBase' => ItemBase::SHIELD
            ],
            ItemBase::PSIONIC_ONE_HAND . ' vs ' . ItemBase::PSIONIC_SHIELD => [
                'lighterBase' => ItemBase::PSIONIC_ONE_HAND,
                'heavierBase' => ItemBase::PSIONIC_SHIELD
            ],
            ItemBase::MACE . ' vs ' . ItemBase::SHIELD => [
                'lighterBase' => ItemBase::MACE,
                'heavierBase' => ItemBase::SHIELD
            ]
        ];
    }

    /**
     * @test
     * @dataProvider provides_higher_grade_items_have_give_more_protection
     * @param $itemBaseName
     */
    public function higher_grade_items_have_give_more_protection($itemBaseName)
    {
        $itemTypes = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->orderBy('grade')->get();

        /** @var ItemType $lowerGradeItemType */
        $lowerGradeItemType = $itemTypes->shift();
        /** @var ItemType $higherGradeItemType */
        $higherGradeItemType = $itemTypes->shift();

        $this->item->item_type_id = $lowerGradeItemType->id;
        $this->item->save();
        $lowerGradeProtection = $this->item->fresh()->getProtection();

        $this->item->item_type_id = $higherGradeItemType->id;
        $this->item->save();
        $higherGradeProtection = $this->item->fresh()->getProtection();

        $this->assertGreaterThan($lowerGradeProtection, $higherGradeProtection);
    }

    public function provides_higher_grade_items_have_give_more_protection()
    {
        return [
            ItemBase::SHIELD => [
                'itemBaseName' => ItemBase::SHIELD
            ],
            ItemBase::HEAVY_ARMOR => [
                'itemBaseName' => ItemBase::HEAVY_ARMOR
            ],
            ItemBase::ROBES => [
                'itemBaseName' => ItemBase::ROBES
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_items_of_some_item_bases_give_no_protection
     * @param $itemBaseName
     */
    public function items_of_some_item_bases_give_no_protection($itemBaseName)
    {
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $protection = $this->item->fresh()->getProtection();

        $this->assertEquals(0, $protection);
    }

    public function provides_items_of_some_item_bases_give_no_protection()
    {
        return [
            ItemBase::SWORD => [
                'itemBaseName' => ItemBase::SWORD
            ],
            ItemBase::BOW => [
                'itemBaseName' => ItemBase::BOW
            ],
            ItemBase::ORB => [
                'itemBaseName' => ItemBase::ORB
            ],
            ItemBase::CROWN => [
                'itemBaseName' => ItemBase::CROWN
            ],
            ItemBase::RING => [
                'itemBaseName' => ItemBase::RING
            ],
        ];
    }




}
