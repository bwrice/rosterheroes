<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadSellsItemsEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadSellsItemsBehavior;
use App\Domain\Models\ProvinceEvent;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateSquadSellsItemsEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateSquadSellsItemsEvent
     */
    protected function getDomainAction()
    {
        return app(CreateSquadSellsItemsEvent::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_squad_sells_item_province_event()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $itemsCount = rand(2, 10);
        $gold = rand(100, 10000);

        $provinceEvent = $this->getDomainAction()->execute($squad, $shop, $shop->province, $itemsCount, $gold, now());

        $this->assertEquals($squad->id, $provinceEvent->squad_id);
        $this->assertEquals($shop->province_id, $provinceEvent->province_id);
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_SELLS_ITEMS, $provinceEvent->event_type);

        /** @var SquadSellsItemsBehavior $behavior */
        $behavior = $provinceEvent->getBehavior();

        $this->assertEquals((string) $shop->uuid, $behavior->getShopUuid());
        $this->assertEquals($itemsCount, $behavior->getItemsCount());
        $this->assertEquals($gold, $behavior->getGold());
    }
}
