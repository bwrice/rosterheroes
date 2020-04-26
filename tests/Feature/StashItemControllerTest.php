<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StashItemControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function getResponse(Squad $squad, Item $item)
    {
        return $this->json('POST', 'api/v1/squads/' . $squad->slug . '/stash-item', [
            'item' => $item->uuid
        ]);
    }

    /**
     * @test
     */
    public function a_user_must_own_the_stash_to_be_authorized_to_stash_an_item()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);

        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);

        $response = $this->getResponse($squad, $item);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_expected_stashed_item_response()
    {
        $this->withoutExceptionHandling();
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);

        Passport::actingAs($squad->user);

        $response = $this->getResponse($squad, $item);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $item->uuid,
                    'transaction' => [
                        'to' => $squad->getLocalStash()->getTransactionIdentification(),
                        'from' => $squad->getTransactionIdentification()
                    ]
                ]
            ]);
    }
}
