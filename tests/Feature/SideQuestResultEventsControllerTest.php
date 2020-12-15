<?php

namespace Tests\Feature;

use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\User;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SideQuestResultEventsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_the_side_quest_events_for_the_side_quest_result()
    {
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->create();
        $eventFactory = SideQuestEventFactory::new()->withSideQuestResultID($sideQuestResult->id);
        $momentFiveEvent = $eventFactory->withMoment(5)->withEventType(SideQuestEvent::TYPE_HERO_DAMAGES_MINION)->create();
        $momentOneEvent = $eventFactory->withMoment(1)->withEventType(SideQuestEvent::TYPE_MINION_DAMAGES_HERO)->create();

        Passport::actingAs($sideQuestResult->campaignStop->campaign->squad->user);

        $response = $this->json('GET', 'api/v1/side-quest-results/' . $sideQuestResult->uuid . '/events');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(2, count($data));

        $response->assertJson([
            'data' => [
                [
                    'uuid' => $momentOneEvent->uuid
                ],
                [
                    'uuid' => $momentFiveEvent->uuid
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_side_quest_events_dont_belong_to_the_user()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $eventFactory = SideQuestEventFactory::new()->withSideQuestResultID($sideQuestResult->id);

        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('GET', 'api/v1/side-quest-results/' . $sideQuestResult->uuid . '/events');

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_throw_a_validation_exception_if_the_side_quest_result_has_not_processed_yet()
    {

        $sideQuestResult = SideQuestResultFactory::new()->create();
        $eventFactory = SideQuestEventFactory::new()->withSideQuestResultID($sideQuestResult->id);

        Passport::actingAs($sideQuestResult->campaignStop->campaign->squad->user);

        $this->assertNull($sideQuestResult->combat_processed_at);

        $response = $this->json('GET', 'api/v1/side-quest-results/' . $sideQuestResult->uuid . '/events');
        $response->assertStatus(422);
    }
}
