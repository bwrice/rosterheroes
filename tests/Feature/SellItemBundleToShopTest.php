<?php

namespace Tests\Feature;

use App\Domain\Actions\SellItemBundleToShop;
use App\Domain\Actions\SellItemToShop;
use App\Domain\Collections\ItemCollection;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
