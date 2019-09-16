<?php

namespace Tests\Unit;

use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\MaterialType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemUnitTest extends TestCase
{
    /** @var Item */
    protected $item;

    public function setUp(): void
    {
        parent::setUp();
        $this->item = factory(Item::class)->create();
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
        $firstMaterial = Material::query()->where('material_type_id', '=', $materialType1_ID)->inRandomOrder()->first();
        /** @var Material $secondMaterial */
        $secondMaterial = Material::query()->where('material_type_id', '=', $materialType2_ID)->get()->first(function (Material $material) use ($firstMaterial) {
            $gradeDiff = abs($material->grade - $firstMaterial->grade);
            return $gradeDiff < 15;
        });

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
     */
    public function a_two_handed_weapon_weighs_more_than_single_hand_weapon()
    {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shields_are_very_heavy()
    {
        //TODO
        $this->assertTrue(true);
    }
}
