<?php

namespace Tests\Unit;

use App\Item;
use App\ItemBlueprint;
use App\ItemClass;
use App\ItemType;
use App\MaterialType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function an_item_can_be_generated()
    {
        /** @var ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create();
        /** @var ItemClass $itemClass */
        $itemClass = ItemClass::inRandomOrder()->first();
        /** @var ItemType $itemType */
        $itemType = ItemType::inRandomOrder()->first();
        /** @var MaterialType $materialType */
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        /** @var Item $item */
        $item = Item::generate($itemClass, $itemType, $materialType, $blueprint);

        $this->assertEquals($itemClass->id, $item->itemClass->id);
        $this->assertEquals($itemType->id, $item->itemType->id);
        $this->assertEquals($materialType->id, $item->materialType->id);
        $this->assertEquals($blueprint->id, $item->itemBlueprint->id);
    }
}
