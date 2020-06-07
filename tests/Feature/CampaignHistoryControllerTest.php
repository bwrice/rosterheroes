<?php

namespace Tests\Feature;

use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CampaignHistoryControllerTest extends TestCase
{

    /**
     * @test
     */
    public function it_will_return_a_squads_historic_campaigns()
    {
        $this->withoutExceptionHandling();

        /** @var Week $previousWeek */
        $previousWeek = factory(Week::class)->create();
        $squad = SquadFactory::new()->create();

        $campaignFactory = CampaignFactory::new()->withSquadID($squad->id)->withWeekID($previousWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResult = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory)->create();

        /** @var Week $previousWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();

        /** Make a campaign for the current week to assert its not there */
        CampaignFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/campaign-history');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(1, count($data));

        $campaignStop = $sideQuestResult->campaignStop;
        $campaign = $campaignStop->campaign;

        $response->assertJson([
            'data' => [
                [
                    'uuid' => (string) $campaign->uuid,
                    'stops' => [
                        [
                            'uuid' => (string) $campaignStop->uuid
                        ]
                    ]
                ]
            ]
        ]);
    }
}
