<?php

namespace Tests\Feature;

use App\Domain\Actions\UpdateShopStock;
use App\Domain\Models\Item;
use App\Domain\Models\ItemClass;
use App\Factories\Models\ItemBlueprintFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateShopStockTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return UpdateShopStock
     */
    protected function getDomainAction()
    {
        return app(UpdateShopStock::class);
    }

    /**
     * @test
     */
    public function it_will_fill_the_shop_to_max_capacity_will_available_items()
    {
        $capacity = rand(3, 8);

        $shop = ShopFactory::new()->create();

        $items = ItemFactory::new()->forShop($shop)->create([], $capacity + 5);

        $items->each(function (Item $item) {
            $this->assertNull($item->made_shop_available_at);
        });

        $shopMock = \Mockery::mock($shop)->shouldReceive(['getStockCapacity' => $capacity, 'getBackInventoryCapacity' => 0])->getMock();

        $this->getDomainAction()->execute($shopMock);

        $items = $items->fresh();
        $this->assertEquals($capacity, $items->shopAvailable()->count());
    }

    /**
     * @test
     */
    public function it_will_move_current_available_items_to_the_back_inventory()
    {
        $capacity = rand(3, 8);

        $shop = ShopFactory::new()->create();

        $itemFactory = ItemFactory::new()->forShop($shop);
        $previouslyAvailableItems = $itemFactory->shopAvailable()->create([], $capacity);
        $backInventoryItems =$itemFactory->create([], $capacity);


        $shopMock = \Mockery::mock($shop)->shouldReceive(['getStockCapacity' => $capacity, 'getBackInventoryCapacity' => 0])->getMock();

        $this->getDomainAction()->execute($shopMock);

        $this->assertEquals($capacity, $shop->availableItems()->count());

        $previouslyAvailableItems->fresh()->each(function (Item $item) {
            $this->assertNull($item->made_shop_available_at);
        });
        $backInventoryItems->fresh()->each(function (Item $item) {
            $this->assertNotNull($item->made_shop_available_at);
        });
    }

    /**
     * @test
     */
    public function it_will_create_items_using_blueprint_to_fill_capacity()
    {
        $shop = ShopFactory::new()->create();

        $capacity = rand(3, 8);

        $itemBlueprint = ItemBlueprintFactory::new()->create();

        $shopMock = \Mockery::mock($shop)->shouldReceive(
            [
                'getStockCapacity' => $capacity,
                'getBackInventoryCapacity' => 0,
                'getStockFillingBlueprint' => $itemBlueprint
            ])->getMock();

        $this->getDomainAction()->execute($shopMock);

        $availableItems = $shop->availableItems()->get();
        $this->assertEquals($capacity, $availableItems->count());

        $availableItems->each(function (Item $item) use ($itemBlueprint) {
            return $item->item_blueprint_id === $itemBlueprint->id;
        });
    }
}
