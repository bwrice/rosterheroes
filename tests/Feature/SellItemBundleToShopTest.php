<?php

namespace Tests\Feature;

use App\Domain\Actions\SellItemBundleToShop;
use App\Domain\Actions\SellItemToShop;
use App\Domain\Collections\ItemCollection;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\CreateSquadSellsItemsEventJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SellItemBundleToShopTest extends TestCase
{
    /**
     * @return SellItemBundleToShop
     */
    protected function getDomainAction()
    {
        return app(SellItemBundleToShop::class);
    }

    /**
     * @test
     */
    public function it_will_call_sell_item_to_shop_action_for_each_item()
    {
        $items = new ItemCollection();
        $itemFactory = ItemFactory::new();

        for($i = 1; $i <= 5; $i++) {
            $items->push($itemFactory->create());
        }

        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $this->mock(SellItemToShop::class)
            ->shouldReceive('execute')
            ->times(5)
            ->andReturn(new ItemCollection());

        $this->getDomainAction()->execute($items, $squad, $shop);
    }

    /**
     * @test
     */
    public function it_will_dispatch_create_squad_sells_items_event_job()
    {
        $itemA = ItemFactory::new()->create();
        $itemA->shop_acquisition_cost = $goldCostA = rand(1, 999);

        $itemB = ItemFactory::new()->create();
        $itemB->shop_acquisition_cost = $goldCostB = rand(1, 999);

        $itemsSold = new ItemCollection([
            $itemA,
            $itemB
        ]);

        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $this->mock(SellItemToShop::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn(new ItemCollection());

        Queue::fake();

        $this->getDomainAction()->execute($itemsSold, $squad, $shop);

        Queue::assertPushed(CreateSquadSellsItemsEventJob::class, function (CreateSquadSellsItemsEventJob $job) use ($goldCostA, $goldCostB) {
            return $job->itemsCount == 2 && $job->gold == ($goldCostA + $goldCostB);
        });
    }
}
