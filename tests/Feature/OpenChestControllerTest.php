<?php

namespace Tests\Feature;

use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\User;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class OpenChestControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_fail_authorization_if_the_chest_does_not_belong_to_the_user()
    {
        $chest = ChestFactory::new()->create();
        /** @var User $diffUser */
        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);

        $this->assertNotEquals($chest->squad->user_id, $diffUser->id);

        $response = $this->json('POST', 'api/v1/chests/' . $chest->uuid . '/open');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_expected_json_result_of_opening_a_chest()
    {
        $this->withoutExceptionHandling();
        /** @var Material $material */
        $material = Material::query()->inRandomOrder()->first();
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->inRandomOrder()->first();
        /** @var ItemType $heaveItemType */
        $itemFactory = ItemFactory::new()->withMaterial($material)->withItemType($itemType);
        $itemOne = $itemFactory->create();
        $itemTwo = $itemFactory->create();

        $chest = ChestFactory::new()->create();
        $chest->items()->save($itemOne);
        $chest->items()->save($itemTwo);
        $chestGold = $chest->gold;

        Passport::actingAs($chest->squad->user);

        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($itemOne->weight())
            ->getMock();

        app()->instance(WagonBehavior::class, $wagonBehaviorMock);

        $response = $this->json('POST', 'api/v1/chests/' . $chest->uuid . '/open');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'gold' => $chestGold,
                    'items' => [
                        [
                            'uuid' => $itemOne->uuid,
                            'hasItems' => [
                                'uuid' => $chest->squad->uuid,
                                'type' => 'squad'
                            ]
                        ],
                        [
                            'uuid' => $itemTwo->uuid,
                            'hasItems' => [
                                'uuid' => $chest->squad->getLocalStash()->uuid,
                                'type' => 'stash'
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
