<?php

namespace Tests\Feature;

use App\Domain\Models\User;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SideQuestBattlegroundControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_unauthorize_a_user_if_the_side_quest_result_does_not_belong_to_them()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        Passport::actingAs(factory(User::class)->create());
        $response = $this->json('GET', '/api/v1/side-quest-results/' . $sideQuestResult->uuid . '/battleground');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_a_side_quest_result_battleground_response()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $eventFactory = SideQuestEventFactory::new()->withSideQuestResultID($sideQuestResult->id);
        $battleGroundSetEvent = $eventFactory->battleGroundSet()->create();

        Passport::actingAs($sideQuestResult->campaignStop->campaign->squad->user);

        $response = $this->json('GET', '/api/v1/side-quest-results/' . $sideQuestResult->uuid . '/battleground');
        $response->assertStatus(200);

        $response->assertJson([
            'uuid' => $battleGroundSetEvent->uuid,
            'data' => [
                'combat_squad' => $battleGroundSetEvent->data['combat_squad'],
                'side_quest_group' => $battleGroundSetEvent->data['side_quest_group']
            ]
        ]);
    }
}
