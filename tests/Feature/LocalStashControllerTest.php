<?php

namespace Tests\Feature;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LocalStashControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_be_unauthorized_to_access_a_stash_of_a_squad_not_manageable_by_the_user()
    {
        $squad = SquadFactory::new()->create();
        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);
        $response = $this->json('GET', $this->getEndpoint($squad));
        $response->assertStatus(403);
    }

    protected function getEndpoint(Squad $squad)
    {
        return 'api/v1/squads/' . $squad->slug . '/current-location/stash';
    }

    /**
     * @test
     */
    public function it_will_return_the_expected_json_response_for_the_stash_at_the_squads_current_location()
    {
        $squad = SquadFactory::new()->create();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();

        $itemOne = ItemFactory::new()->create();
        $stash->items()->save($itemOne);
        $itemTwo = ItemFactory::new()->create();
        $stash->items()->save($itemTwo);

        Passport::actingAs($squad->user);
        $response = $this->json('GET', $this->getEndpoint($squad));
        $response->assertStatus(200)->assertJson([
            'data' => [
                'items' => [
                    [
                        'uuid' => $itemOne->uuid
                    ],
                    [
                        'uuid' => $itemTwo->uuid
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_return_an_empty_stash_response_if_no_stash_exists_for_the_squads_current_location()
    {
        $squad = SquadFactory::new()->create();
        $diffProvince = Province::query()->where('id', '!=', $squad->province_id)->inRandomOrder()->first();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($diffProvince)->create();

        $itemOne = ItemFactory::new()->create();
        $stash->items()->save($itemOne);
        $itemTwo = ItemFactory::new()->create();
        $stash->items()->save($itemTwo);

        Passport::actingAs($squad->user);
        $response = $this->json('GET', $this->getEndpoint($squad));
        $response->assertStatus(201);

        $data = $response->json(['data']);
        $this->assertEquals(0, count($data['items']));
    }
}
