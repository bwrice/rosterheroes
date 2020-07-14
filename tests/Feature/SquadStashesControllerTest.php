<?php

namespace Tests\Feature;

use App\Domain\Models\Province;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SquadStashesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_user_must_own_a_squad_to_view_stashes()
    {
        $squad = SquadFactory::new()->create();

        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/stashes');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_stashes_with_items()
    {
        $squad = SquadFactory::new()->create();

        $stash = StashFactory::new()->withSquadID($squad->id)->create();

        $item = ItemFactory::new()->create();
        $item->hasItems()->associate($stash);
        $item->save();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/stashes');
        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'uuid' => $stash->uuid
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_not_return_stashes_without_items()
    {
        $squad = SquadFactory::new()->create();

        $stash = StashFactory::new()->withSquadID($squad->id)->create();

        $diffProvince = Province::query()->where('id', '!=', $stash->province_id)->inRandomOrder()->first();
        $emptyStash = StashFactory::new()->withSquadID($squad->id)->atProvince($diffProvince)->create();

        $item = ItemFactory::new()->create();
        $item->hasItems()->associate($stash);
        $item->save();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/stashes');
        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'uuid' => $stash->uuid
                ]
            ]
        ]);

        $this->assertEquals(1, count($response->json('data')));
    }
}
