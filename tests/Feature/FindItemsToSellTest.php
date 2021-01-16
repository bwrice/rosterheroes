<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindItemsToSell;
use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Models\Item;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\MaterialType;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FindItemsToSellTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindItemsToSell
     */
    protected function getDomainAction()
    {
        return app(FindItemsToSell::class);
    }

    /**
     * @test
     */
    public function it_will_return_items_to_get_mobile_storage_capacity_below_half_but_not_completely_empty()
    {
        $npc = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($npc->province_id)->create();

        /** @var ItemType $itemType */
        $itemType = ItemType::query()->inRandomOrder()->first();
        /** @var MaterialType $materialType */
        $materialType = $itemType->itemBase->materialTypes()->inRandomOrder()->first();
        /** @var Material $material */
        $material = $materialType->materials()->inRandomOrder()->first();
        $itemFactory = ItemFactory::new()
            ->forSquad($npc)
            ->withItemType($itemType)
            ->withMaterial($material);
        $items = collect();
        $itemsCount = 10;
        for ($i = 1; $i <= $itemsCount; $i++) {
            $items->push($itemFactory->create());
        }
        /** @var Item $item */
        $item = $items->first();
        $weight = $item->weight();

        /*
         * All the items should be same weight since they are same item-type and material
         * Mock mobile storage capacity to be completely full from items created
         */
        $capacityMock = $weight * $itemsCount;
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($capacityMock)
            ->getMock();
        $this->app->instance(WagonBehavior::class, $wagonBehaviorMock);

        $returnValue = $this->getDomainAction()->execute($npc);
        /** @var Collection $items */
        $items = $returnValue['items'];

        $this->assertGreaterThanOrEqual(5, $items->count());
        $this->assertLessThanOrEqual(8, $items->count());
    }

    /**
     * @test
     */
    public function it_will_prioritize_returning_items_of_lower_quality()
    {
        $npc = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($npc->province_id)->create();

        /** @var ItemType $itemType */
        $itemType = ItemType::query()->inRandomOrder()->first();
        /** @var MaterialType $materialType */
        $materialType = $itemType->itemBase->materialTypes()->inRandomOrder()->first();
        /** @var Material $material */
        $material = $materialType->materials()->inRandomOrder()->first();
        $itemsCount = 20;
        $itemFactory = ItemFactory::new()
            ->forSquad($npc)
            ->withItemType($itemType)
            ->withMaterial($material);
        $lowQualityItems = collect();
        for ($i = 1; $i <= $itemsCount/2; $i++) {
            $lowQualityItems->push($itemFactory->create());
        }

        $itemFactory = $itemFactory->withEnchantments();
        $highQualityItems = collect();

        for ($i = 1; $i <= $itemsCount/2; $i++) {
            $highQualityItems->push($itemFactory->create());
        }

        /*
         * All the items should be same weight since they are same item-type and material
         * Mock mobile storage capacity to be completely full from items created
         */
        $weight = $lowQualityItems->first()->weight();
        $capacityMock = $weight * $itemsCount;
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($capacityMock)
            ->getMock();
        $this->app->instance(WagonBehavior::class, $wagonBehaviorMock);

        $returnValue = $this->getDomainAction()->execute($npc);
        /** @var Collection $returnedItems */
        $returnedItems = $returnValue['items'];

        $this->assertLessThanOrEqual($itemsCount - 2, $returnedItems->count());

        /*
         * Verify all low quality items are returned to be sold
         */
        $returnedItemIDs = $returnedItems->pluck('id')->toArray();
        $lowQualityItems->each(function (Item $item) use ($returnedItemIDs) {
            $this->assertTrue(in_array($item->id, $returnedItemIDs), "Failed asserting low quality item in returned items");
        });
    }

    /**
     * @test
     */
    public function it_will_return_null_if_mobile_storage_is_barely_full()
    {
        $npc = SquadFactory::new()->create();
        for ($i = 1; $i <= 3; $i++) {
            ItemFactory::new()->forSquad($npc)->create();
        }
        $returnValue = $this->getDomainAction()->execute($npc);
        $this->assertNull($returnValue);
    }
}
