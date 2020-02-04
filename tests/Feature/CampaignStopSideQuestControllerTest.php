<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CampaignStopSideQuestControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    /** @var Campaign */
    protected $campaign;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Quest */
    protected $quest;

    /** @var SideQuest */
    protected $sideQuest;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->week->adventuring_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);
        $this->squad = factory(Squad::class)->create();
        $this->quest = factory(Quest::class)->create([
            'province_id' => $this->squad->province_id
        ]);
        $this->sideQuest = factory(SideQuest::class)->create([
            'quest_id' => $this->quest->id
        ]);
        $this->campaign = factory(Campaign::class)->create([
            'continent_id' => $this->quest->province->continent_id,
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id
        ]);

        $this->campaignStop = factory(CampaignStop::class)->create([
            'quest_id' => $this->quest->id,
            'province_id' => $this->quest->province_id,
            'campaign_id' => $this->campaign->id
        ]);
    }

    /**
     * @test
     */
    public function a_user_must_own_the_squad_of_the_campaign_stop_to_add_side_quest()
    {
        Passport::actingAs(factory(User::class)->create());

        $campaignStopUuid = $this->campaignStop->uuid;
        $sideQuestUuid = $this->sideQuest->uuid;

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/side-quests', [
            'sideQuest' => $sideQuestUuid
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_throw_a_validation_error_if_a_campaign_stop_exception_is_caught()
    {
        Passport::actingAs($this->squad->user);

        $campaignStopUuid = $this->campaignStop->uuid;
        $sideQuestUuid = $this->sideQuest->uuid;

        $mock = \Mockery::mock(app(JoinSideQuestAction::class))
            ->shouldReceive('execute')
            ->andThrow(new CampaignStopException())->getMock();

        // Use the mock when retrieving from the container
        app()->instance(JoinSideQuestAction::class, $mock);

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/side-quests', [
            'sideQuest' => $sideQuestUuid
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors([
            'campaign'
        ]);
    }

    /**
     * @test
     */
    public function it_will_add_a_side_quest_and_return_an_updated_campaign_stop()
    {
        Passport::actingAs($this->squad->user);

        $campaignStopUuid = $this->campaignStop->uuid;
        $sideQuestUuid = $this->sideQuest->uuid;

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/side-quests', [
            'sideQuest' => $sideQuestUuid
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $this->campaign->uuid,
                    'campaignStops' => [
                        [
                            'uuid' => $this->campaignStop->uuid,
                            'sideQuestUuids' => [
                                $this->sideQuest->uuid
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_will_leave_a_side_quest_and_return_an_updated_campaign_stop()
    {
        $this->campaignStop->sideQuests()->attach($this->sideQuest->id);

        Passport::actingAs($this->squad->user);

        $campaignStopUuid = $this->campaignStop->uuid;
        $sideQuestUuid = $this->sideQuest->uuid;

        $response = $this->json('DELETE', 'api/v1/campaign-stops/' . $campaignStopUuid . '/side-quests', [
            'sideQuest' => $sideQuestUuid
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $this->campaign->uuid,
                    'campaignStops' => [
                        [
                            'uuid' => $this->campaignStop->uuid,
                            'sideQuestUuids' => [
                                // No side_quests left
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(0, $this->campaignStop->fresh()->sideQuests()->count());
    }
}
