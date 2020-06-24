<?php

namespace Tests\Feature;

use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SellToShopControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_must_own_the_squad_to_sell_items_to_a_shop()
    {
        $squad = SquadFactory::new()->create();
        $itemFactory = ItemFactory::new()->forHasItems($squad);
        $itemOne = $itemFactory->create();
        $itemTwo = $itemFactory->create();
        $itemThree = $itemFactory->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug . '/sell', [
            'items' => [
                $itemOne->uuid,
                $itemTwo->uuid,
                $itemThree->uuid
            ]
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function sell_items_will_return_and_items_response_with_their_transactions()
    {
        $this->withoutExceptionHandling();
        $squad = SquadFactory::new()->create();
        $itemFactory = ItemFactory::new()->forHasItems($squad);
        $itemOne = $itemFactory->create();
        $itemTwo = $itemFactory->create();
        $itemThree = $itemFactory->create();

        $itemsToSell = new ItemCollection([
            $itemOne,
            $itemTwo,
            $itemThree
        ]);

        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        Passport::actingAs($squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug . '/sell', [
            'items' => $itemsToSell->pluck('uuid')->toArray()
        ]);

        $itemsMoved = collect($response->json('data'));
        $this->assertEquals(3, $itemsMoved->count());

        $itemsToSell->each(function (Item $item) use ($itemsMoved, $squad, $shop) {
            $match = $itemsMoved->first(function ($itemMoved) use ($item) {
                return $itemMoved['uuid'] === (string) $item->uuid;
            });
            $this->assertNotNull($match);
            $this->assertEquals($match['transaction']['from'], $squad->getTransactionIdentification());
            $this->assertEquals($match['transaction']['to'], $shop->getTransactionIdentification());
        });
    }
}
