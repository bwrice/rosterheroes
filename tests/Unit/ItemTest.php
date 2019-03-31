<?php

namespace Tests\Unit;

use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\MaterialType;
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
        /** @var \App\Domain\Models\ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create();
        /** @var ItemClass $itemClass */
        $itemClass = ItemClass::inRandomOrder()->first();
        /** @var ItemType $itemType */
        $itemType = ItemType::inRandomOrder()->first();
        /** @var MaterialType $materialType */
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Item $item */
        $item = Item::generate($itemClass, $itemType, $materialType, $blueprint);

        $this->assertEquals($itemClass->id, $item->itemClass->id);
        $this->assertEquals($itemType->id, $item->itemType->id);
        $this->assertEquals($materialType->id, $item->materialType->id);
        $this->assertEquals($blueprint->id, $item->itemBlueprint->id);
    }
}
