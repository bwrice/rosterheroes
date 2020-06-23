<?php

namespace Tests\Feature;

use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BuyItemFromShopControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_user_must_own_the_squad_to_purchase_items_for_it()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create();

        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('POST','api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug . '/buy', [
            'item' => $item->uuid
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function buying_an_item_will_return_an_item_collection_response_with_transactions()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();
        $item = ItemFactory::new()->forHasItems($shop)->shopAvailable()->create();

        Passport::actingAs($squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug . '/buy', [
            'item' => $item->uuid
        ]);

        $itemsMoved = $response->json('data');

        $this->assertEquals(1, count($itemsMoved));

        $itemData = $itemsMoved[0];

        $this->assertEquals($itemData['uuid'], $item->uuid);
        $this->assertEquals($itemData['transaction']['to'], $squad->getTransactionIdentification());
        $this->assertEquals($itemData['transaction']['from'], $shop->getTransactionIdentification());
    }
}
