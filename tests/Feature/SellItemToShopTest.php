<?php

namespace Tests\Feature;

use App\Domain\Actions\SellItemToShop;
use App\Domain\Models\Province;
use App\Domain\Models\Shop;
use App\Exceptions\SellItemToShopException;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\Mock;
use Tests\TestCase;

class SellItemToShopTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return SellItemToShop
     */
    protected function getDomainAction()
    {
        return app(SellItemToShop::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_the_squad()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->create();

        try {

            $this->getDomainAction()->execute($item, $squad, $shop);

        } catch (SellItemToShopException $exception) {

            $this->assertEquals(SellItemToShopException::CODE_INVALID_ITEM_OWNERSHIP, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_at_the_same_province_as_the_shop()
    {
        $squad = SquadFactory::new()->create();
        $diffProvinceID = $squad->province_id === 1 ? 2 : $squad->province_id - 1;
        $shop = ShopFactory::new()->withProvinceID($diffProvinceID)->create();
        $item = ItemFactory::new()->forSquad($squad)->create();

        try {

            $this->getDomainAction()->execute($item, $squad, $shop);

        } catch (SellItemToShopException $exception) {

            $this->assertEquals(SellItemToShopException::CODE_INVALID_PROVINCE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_move_the_item_to_the_shop()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forSquad($squad)->create();

        $this->getDomainAction()->execute($item, $squad, $shop);

        $item = $item->fresh();
        $hasItems = $item->hasItems;
        $this->assertEquals($hasItems->getMorphID(), $shop->id);
        $this->assertEquals($hasItems->getMorphType(), Shop::RELATION_MORPH_MAP_KEY);
    }

    /**
     * @test
     */
    public function it_will_increase_the_squads_gold_by_the_sale_price()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        $salePrice = rand(10, 500);
        $shopMock = \Mockery::mock($shop)->shouldReceive('getSalePrice')->andReturn($salePrice)->getMock();
        $item = ItemFactory::new()->forSquad($squad)->create();

        $previousGold = $squad->gold;

        $this->getDomainAction()->execute($item, $squad, $shopMock);

        $squad = $squad->fresh();
        $this->assertEquals($previousGold + $salePrice, $squad->gold);
    }
}
