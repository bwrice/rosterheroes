<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadSellsItemsEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadSellsItemsBehavior;
use App\Domain\Models\ProvinceEvent;
use App\Events\NewProvinceEvent;
use App\Factories\Models\ProvinceEventFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
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

    /**
     * @test
     */
    public function it_dispatch_new_province_event_after_creating_squad_sells_item_event()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $itemsCount = rand(2, 10);
        $gold = rand(100, 10000);

        Event::fake();
        $this->getDomainAction()->execute($squad, $shop, $shop->province, $itemsCount, $gold, now());

        Event::assertDispatched(NewProvinceEvent::class);
    }

    /**
     * @test
     */
    public function it_will_update_a_matching_recently_created_squad_sells_item_event()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $initialItemsCount = rand(2, 10);
        $initialGold = rand(100, 10000);

        $now = now();
        $recentProvinceEvent = ProvinceEventFactory::new()
            ->squadSellsItems($shop, $initialItemsCount, $initialGold)
            ->forSquad($squad)
            ->at($now->subMinutes(CreateSquadSellsItemsEvent::RECENT_MINUTES - 1))
            ->create();

        $additionalItemsCount = rand(2, 10);
        $additionalGold = rand(100, 10000);

        $updatedEvent = $this->getDomainAction()->execute($squad, $shop, $shop->province, $additionalItemsCount, $additionalGold, $now);

        $this->assertEquals($recentProvinceEvent->id, $updatedEvent->id);

        /** @var SquadSellsItemsBehavior $behavior */
        $behavior = $updatedEvent->getBehavior();

        $this->assertEquals($initialItemsCount + $additionalItemsCount, $behavior->getItemsCount());
        $this->assertEquals($initialGold + $additionalGold, $behavior->getGold());

        $this->assertEquals($now->timestamp, $updatedEvent->happened_at->timestamp);
    }

    /**
     * @test
     */
    public function it_will_return_a_completely_new_squad_sells_item_event_if_matching_event_not_recent()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->create();
        $initialItemsCount = rand(2, 10);
        $initialGold = rand(100, 10000);

        $now = now();
        $recentProvinceEvent = ProvinceEventFactory::new()
            ->squadSellsItems($shop, $initialItemsCount, $initialGold)
            ->forSquad($squad)
            ->at($now->subMinutes(CreateSquadSellsItemsEvent::RECENT_MINUTES + 1))
            ->create();

        $additionalItemsCount = rand(2, 10);
        $additionalGold = rand(100, 10000);

        $updatedEvent = $this->getDomainAction()->execute($squad, $shop, $shop->province, $additionalItemsCount, $additionalGold, $now);

        $this->assertNotEquals($recentProvinceEvent->id, $updatedEvent->id);

        /** @var SquadSellsItemsBehavior $behavior */
        $behavior = $updatedEvent->getBehavior();

        $this->assertEquals($additionalItemsCount, $behavior->getItemsCount());
        $this->assertEquals($additionalGold, $behavior->getGold());

        $this->assertEquals($now->timestamp, $updatedEvent->happened_at->timestamp);
    }
}
