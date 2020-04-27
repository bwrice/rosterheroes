<?php

namespace Tests\Feature;

use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Models\ItemBase;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MobileStoreItemForSquadControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_fail_authorization_if_the_user_doesnt_own_the_squad()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->getLocalStash()->items()->save($item);

        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/mobile-store-item', [
            'item' => $item->uuid
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_expected_response()
    {
        /*
         * Need a heavy item since we are mocking the wagon capacity based on it's weight
         */
        $itemAlreadyInWagon = ItemFactory::new()->fromItemBases([ItemBase::SHIELD])->create();
        $squad = SquadFactory::new()->create();
        $squad->items()->save($itemAlreadyInWagon);

        /*
         * Need a light item because the wagon capacity is mocked based on the item already in the wagon
         */
        $itemToStore = ItemFactory::new()->fromItemBases([ItemBase::RING])->create();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $stash->items()->save($itemToStore);

        $mock = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($itemAlreadyInWagon->weight())
            ->getMock();

        app()->instance(WagonBehavior::class, $mock);

        Passport::actingAs($squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/mobile-store-item', [
            'item' => $itemToStore->uuid
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'uuid' => $itemAlreadyInWagon->uuid,
                    'transaction' => [
                        'to' => $stash->getTransactionIdentification(),
                        'from' => $squad->getTransactionIdentification()
                    ]
                ],
                [
                    'uuid' => $itemToStore->uuid,
                    'transaction' => [
                        'to' => $squad->getTransactionIdentification(),
                        'from' => $stash->getTransactionIdentification()
                    ]
                ]
            ]
        ]);
    }
}
