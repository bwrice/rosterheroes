<?php

namespace Tests\Feature;

use App\Domain\Models\User;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UnopenedChestControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $squad;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $this->squad = SquadFactory::new()->create([
            'user_id' => $user->id
        ]);
        Passport::actingAs($user);
    }

    /**
     * @test
     */
    public function it_will_return_a_squads_unopened_chests()
    {
        $factory = ChestFactory::new()->withSquadID($this->squad->id);
        $chestForSquadOne = $factory->create();
        $chestForSquadTwo = $factory->create();
        $alreadyOpenedChest = $factory->opened()->create();
        $differenceSquadChest = ChestFactory::new()->create();

        $response = $this->get('/api/v1/squads/' . $this->squad->slug . '/unopened-chests');

        $this->assertEquals(2, count($response->json('data')));

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'uuid' => $chestForSquadOne->uuid
                    ],
                    [
                        'uuid' => $chestForSquadTwo->uuid
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_will_return_unauthorized_response_if_not_the_chests_squads_user()
    {
        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);
        $response = $this->get('/api/v1/squads/' . $this->squad->slug . '/unopened-chests');
        $response->assertStatus(403);
    }
}
