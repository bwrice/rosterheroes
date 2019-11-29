<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinSkirmishAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
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

class CampaignStopSkirmishControllerTest extends TestCase
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

    /** @var Skirmish */
    protected $skirmish;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->week->everything_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);
        $this->squad = factory(Squad::class)->create();
        $this->quest = factory(Quest::class)->create([
            'province_id' => $this->squad->province_id
        ]);
        $this->skirmish = factory(Skirmish::class)->create([
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
    public function a_user_must_own_the_squad_of_the_campaign_stop_to_add_skirmish()
    {
        Passport::actingAs(factory(User::class)->create());

        $campaignStopUuid = $this->campaignStop->uuid;
        $skirmishUuid = $this->skirmish->uuid;

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/skirmishes', [
            'skirmish' => $skirmishUuid
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
        $skirmishUuid = $this->skirmish->uuid;

        $mock = \Mockery::mock(app(JoinSkirmishAction::class))
            ->shouldReceive('execute')
            ->andThrow(new CampaignStopException())->getMock();

        // Use the mock when retrieving from the container
        app()->instance(JoinSkirmishAction::class, $mock);

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/skirmishes', [
            'skirmish' => $skirmishUuid
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors([
            'campaign'
        ]);
    }

    /**
     * @test
     */
    public function it_will_add_a_skirmish_and_return_an_updated_campaign_stop()
    {
        Passport::actingAs($this->squad->user);

        $campaignStopUuid = $this->campaignStop->uuid;
        $skirmishUuid = $this->skirmish->uuid;

        $response = $this->json('POST', 'api/v1/campaign-stops/' . $campaignStopUuid . '/skirmishes', [
            'skirmish' => $skirmishUuid
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $this->campaign->uuid,
                    'campaignStops' => [
                        [
                            'uuid' => $this->campaignStop->uuid,
                            'skirmishUuids' => [
                                $this->skirmish->uuid
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_will_leave_a_skirmish_and_return_an_updated_campaign_stop()
    {
        $this->campaignStop->skirmishes()->attach($this->skirmish->id);

        Passport::actingAs($this->squad->user);

        $campaignStopUuid = $this->campaignStop->uuid;
        $skirmishUuid = $this->skirmish->uuid;

        $response = $this->json('DELETE', 'api/v1/campaign-stops/' . $campaignStopUuid . '/skirmishes', [
            'skirmish' => $skirmishUuid
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'uuid' => $this->campaign->uuid,
                    'campaignStops' => [
                        [
                            'uuid' => $this->campaignStop->uuid,
                            'skirmishUuids' => [
                                // No skirmishes left
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(0, $this->campaignStop->fresh()->skirmishes()->count());
    }
}
