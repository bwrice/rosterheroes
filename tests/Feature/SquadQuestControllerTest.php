<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinQuestAction;
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

        $response = $this->post('/api/v1/squads/' . $this->squad->slug . '/enlist', [
            'quest' => $this->quest->uuid
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_squads_updated_campaign_response()
    {
        Passport::actingAs($this->squad->user);

        $response = $this->post('/api/v1/squads/' . $this->squad->slug . '/enlist', [
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
    public function it_will_return_a_validation_error_if_a_campaign_exception_is_thrown()
    {
        Passport::actingAs($this->squad->user);

        $message = "exception test";
        $actionMock = \Mockery::mock(JoinQuestAction::class)
            ->shouldReceive('execute')->andThrow(new CampaignException($message))->getMock();
        // put the mock into the container
        app()->instance(JoinQuestAction::class, $actionMock);

        $response = $this->json('POST','/api/v1/squads/' . $this->squad->slug . '/enlist', [
            'quest' => $this->quest->uuid
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['campaign']);
    }


}
