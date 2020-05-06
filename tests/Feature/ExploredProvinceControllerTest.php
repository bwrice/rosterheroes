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

    public function setUp(): void
    {
        parent::setUp();
        $this->province = Province::query()->inRandomOrder()->first();
    }

    protected function getEndpoint(Province $province)
    {
        return '/api/v1/explore-provinces/' . $province->slug;
    }

    /**
     * @test
     */
    public function it_will_have_compact_quests_located_at_the_province_in_the_response()
    {
        $questOne = QuestFactory::new()->withProvinceID($this->province->id)->create();
        $questTwo = QuestFactory::new()->withProvinceID($this->province->id)->create();

        $response = $this->json('GET', $this->getEndpoint($this->province));
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
