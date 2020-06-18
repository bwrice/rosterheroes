<?php

namespace Tests\Feature;

use App\Factories\Models\ShopFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LocalMerchantsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_shops_for_the_squads_current_location()
    {
        $this->withoutExceptionHandling();
        $squad = SquadFactory::new()->create();

        Passport::actingAs($squad->user);

        $shop = ShopFactory::new()->withProvinceID($squad->province_id)->create();

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/current-location/merchants');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'type' => 'shop',
                    'slug' => $shop->slug
                ]
            ]
        ]);
    }
}
