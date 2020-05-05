<?php

namespace Tests\Feature;

use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExploredProvinceControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Province */
    protected $province;

    /** @var Squad */
    protected $squad;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->create();
        $this->province = Province::query()->inRandomOrder()->first();
    }

    protected function getEndpoint(Squad $squad, Province $province)
    {
        return '/api/v1/squads/' . $squad->slug .'/explore-province/' . $province->slug;
    }

    /**
     * @test
     */
    public function it_will_unauthorize_exploring_province_if_not_the_user_of_the_squad()
    {
        $diffUser = factory(User::class)->create();
        Passport::actingAs($diffUser);
        $response = $this->json('GET', $this->getEndpoint($this->squad, $this->province));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_a_compact_stash_of_the_squad_located_at_the_province()
    {
        $diffStash = StashFactory::new()->atProvince($this->province)->create();

        $stash = StashFactory::new()->withSquadID($this->squad->id)->atProvince($this->province)->create();

        $itemFactory = ItemFactory::new();
        $itemsCount = rand(1, 4);
        for($i = 1; $i <= $itemsCount; $i++) {
            $stash->items()->save($itemFactory->create());
        }

        Passport::actingAs($this->squad->user);

        $response = $this->json('GET', $this->getEndpoint($this->squad, $this->province));
        $response->assertStatus(200)->assertJson([
            'data' => [
                'squadStash' => [
                    'uuid' => $stash->uuid,
                    'itemsCount' => $itemsCount
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_not_return_a_compact_stash_of_a_different_squad_located_at_the_province()
    {
        $diffStash = StashFactory::new()->atProvince($this->province)->create();

        Passport::actingAs($this->squad->user);

        $response = $this->json('GET', $this->getEndpoint($this->squad, $this->province));
        $response->assertStatus(200)->assertJson([
            'data' => [
                'squadStash' => null
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_have_compact_quests_located_at_the_province_in_the_response()
    {
        $questOne = QuestFactory::new()->withProvinceID($this->province->id)->create();
        $questTwo = QuestFactory::new()->withProvinceID($this->province->id)->create();
        Passport::actingAs($this->squad->user);

        $response = $this->json('GET', $this->getEndpoint($this->squad, $this->province));
        $response->assertStatus(200)->assertJson([
            'data' => [
                'quests' => [
                    [
                        'uuid' => $questOne->uuid
                    ],
                    [
                        'uuid' => $questTwo->uuid
                    ],
                ]
            ]
        ]);
    }
}
