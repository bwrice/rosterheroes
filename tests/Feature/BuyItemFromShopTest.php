<?php

namespace Tests\Feature;

use App\Domain\Actions\AddItemToHasItems;
use App\Domain\Actions\BuyItemFromShop;
use App\Exceptions\BuyItemFromShopException;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuyItemFromShopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuyItemFromShop
     */
    protected function getDomainAction()
    {
        return app(BuyItemFromShop::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_the_shop()
    {
        $squad = SquadFactory::new()->create([
            'gold' => 9999999
        ]);
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $diffShop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($diffShop)->shopAvailable()->create();

        try {

            $this->getDomainAction()->execute($item, $shop, $squad);

        } catch (BuyItemFromShopException $exception) {
            $this->assertEquals(BuyItemFromShopException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_is_not_shop_available()
    {

        $squad = SquadFactory::new()->create([
            'gold' => 9999999
        ]);
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->create();

        $this->assertNull($item->made_shop_available_at);
        try {

            $this->getDomainAction()->execute($item, $shop, $squad);

        } catch (BuyItemFromShopException $exception) {
            $this->assertEquals(BuyItemFromShopException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_in_the_shops_province()
    {
        $squad = SquadFactory::new()->create([
            'gold' => 9999999
        ]);

        $diffProvinceID = $squad->province_id == 1 ? 2 : $squad->province_id - 1;

        $shop = ShopFactory::new()->withProvinceID($diffProvinceID)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create();

        try {

            $this->getDomainAction()->execute($item, $shop, $squad);

        } catch (BuyItemFromShopException $exception) {
            $this->assertEquals(BuyItemFromShopException::CODE_INVALID_PROVINCE, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_does_not_have_enough_gold()
    {

        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create();

        $purchasePrice = rand(100, 999);
        $mockShop = \Mockery::mock($shop)
            ->shouldReceive('getPurchasePrice')
            ->andReturn($purchasePrice)
            ->getMock();

        $squad->gold = $purchasePrice - 1;
        $squad->save();
        $squad = $squad->fresh();

        try {

            $this->getDomainAction()->execute($item, $mockShop, $squad);

        } catch (BuyItemFromShopException $exception) {
            $this->assertEquals(BuyItemFromShopException::CODE_NOT_ENOUGH_GOLD, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_decrease_the_squads_gold_by_the_purchase_price_of_the_item()
    {
        $squadGold = rand(1000, 9999);
        $squad = SquadFactory::new()->create([
            'gold' => $squadGold
        ]);
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create();

        $purchasePrice = rand(100, 999);
        $mockShop = \Mockery::mock($shop)
            ->shouldReceive('getPurchasePrice')
            ->andReturn($purchasePrice)
            ->getMock();

        $this->getDomainAction()->execute($item, $mockShop, $squad);

        $this->assertEquals($squadGold - $purchasePrice, $squad->fresh()->gold);
    }

    /**
     * @test
     */
    public function the_purchased_item_will_belong_to_the_squad_with_cleared_shop_values()
    {
        $squadGold = rand(1000, 9999);
        $squad = SquadFactory::new()->create([
            'gold' => $squadGold
        ]);
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create([
            'shop_acquired_at' => Date::now()->subDays(5),
            'shop_acquisition_cost' => rand(50, 999)
        ]);

        $purchasePrice = rand(100, 999);
        $mockShop = \Mockery::mock($shop)
            ->shouldReceive('getPurchasePrice')
            ->andReturn($purchasePrice)
            ->getMock();

        $this->getDomainAction()->execute($item, $mockShop, $squad);

        $item = $item->fresh();
        $this->assertTrue($item->ownedByMorphable($squad));
        $this->assertNull($item->made_shop_available_at);
        $this->assertNull($item->shop_acquired_at);
        $this->assertNull($item->shop_acquisition_cost);
    }
}
