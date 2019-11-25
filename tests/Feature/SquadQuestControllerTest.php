<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\External\Stats\MySportsFeed\MSFClient;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadQuestControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    /** @var Quest */
    protected $quest;

    public function setUp(): void
    {
        parent::setUp();
        $this->quest = factory(Quest::class)->create();
        $this->squad = factory(Squad::class)->create([
            'province_id' => $this->quest->province_id
        ]);
        $this->week = factory(Week::class)->create();
        $this->week->everything_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);
    }

    /**
     * @test
     */
    public function user_must_own_squad_to_enlist()
    {
        Passport::actingAs(factory(User::class)->create());

            $response = $this->json('POST','/api/v1/squads/' . $this->squad->slug . '/quests', [
            'quest' => $this->quest->uuid
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_squads_updated_campaign_response_when_joining_quest()
    {
        Passport::actingAs($this->squad->user);

        $response = $this->json('POST','/api/v1/squads/' . $this->squad->slug . '/quests', [
            'quest' => $this->quest->uuid
        ]);

        $currentCampaign = $this->squad->fresh()->getCurrentCampaign();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $currentCampaign->uuid
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_will_return_null_when_leaving_quest_of_single_quest_campaign()
    {
        // Create campaign
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id,
            'continent_id' => $this->quest->province->continent_id
        ]);

        // create campaign stop for quest and campaign
        factory(CampaignStop::class)->create([
            'campaign_id' => $campaign->id,
            'quest_id' => $this->quest->id
        ]);

        Passport::actingAs($this->squad->user);

        $response = $this->json('DELETE','/api/v1/squads/' . $this->squad->slug . '/quests', [
            'quest' => $this->quest->uuid
        ]);

        $currentCampaign = $this->squad->fresh()->getCurrentCampaign();
        $this->assertNull($currentCampaign);

        $response->assertStatus(200)
            ->assertJson([
                'data' => null
            ]);
    }
    /**
     * @test
     */
    public function it_will_return_an_updated_campaign_when_leaving_quest_of_multi_quest_campaign()
    {
        // Create campaign
        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id,
            'continent_id' => $this->quest->province->continent_id
        ]);

        // create campaign stop for quest and campaign
        factory(CampaignStop::class)->create([
            'campaign_id' => $campaign->id,
            'quest_id' => $this->quest->id
        ]);

        // create campaign stop for different quest but same campaign
        /** @var CampaignStop $otherCampaignStop */
        $otherCampaignStop = factory(CampaignStop::class)->create([
            'campaign_id' => $campaign->id,
        ]);

        Passport::actingAs($this->squad->user);

        $currentCampaign = $this->squad->fresh()->getCurrentCampaign();
        $this->assertEquals(2, $currentCampaign->fresh()->campaignStops()->count());

        $response = $this->json('DELETE','/api/v1/squads/' . $this->squad->slug . '/quests', [
            'quest' => $this->quest->uuid
        ]);

        $currentCampaign = $this->squad->fresh()->getCurrentCampaign();
        $this->assertNotNull($currentCampaign);

        $this->assertEquals(1, $currentCampaign->fresh()->campaignStops()->count());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $campaign->uuid,
                    'campaignStops' => [
                        [
                            'uuid' => $otherCampaignStop->uuid
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_will_return_a_validation_error_if_a_campaign_exception_is_thrown()
    {
        Passport::actingAs($this->squad->user);

        $message = "exception test";
        $actionMock = \Mockery::mock(JoinQuestAction::class)
            ->shouldReceive('execute')->andThrow(new CampaignException($message))->getMock();
        // put the mock into the container
        app()->instance(JoinQuestAction::class, $actionMock);

        $response = $this->json('POST','/api/v1/squads/' . $this->squad->slug . '/quests', [
            'quest' => $this->quest->uuid
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['campaign']);
    }



}
