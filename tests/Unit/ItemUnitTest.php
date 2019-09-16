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
