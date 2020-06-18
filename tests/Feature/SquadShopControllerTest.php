<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SquadShopControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_squad_does_not_belong_to_the_user()
    {
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        // Acting as different user
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_squad_is_not_at_the_same_province_as_the_shop()
    {
        $squad = SquadFactory::new()->create();
        $diffProvinceID = $squad->province_id === 1 ? 2 : $squad->province_id - 1;
        $shop = ShopFactory::new()->withProvinceID($diffProvinceID)->create();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_only_return_items_made_available_for_the_shop()
    {
        $this->withoutExceptionHandling();
        $squad = SquadFactory::new()->create();
        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        $itemOne = ItemFactory::new()->shopAvailable()->forHasItems($shop)->create();
        $itemTwo = ItemFactory::new()->shopAvailable()->forHasItems($shop)->create();

        // Make non-available shop item
        ItemFactory::new()->forHasItems($shop)->create();

        $this->assertEquals(3, $shop->items()->count());

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/shops/' . $shop->slug);
        $itemsResponse = collect($response->json('data')['items']);
        $this->assertCount(2, $itemsResponse);

        foreach([$itemOne, $itemTwo] as $item) {
            /** @var Item $item */
            $match = $itemsResponse->first(function ($itemArray) use ($item) {
                return $itemArray['uuid'] === (string) $item->uuid;
            });

            $this->assertNotNull($match);
        }
    }
}
