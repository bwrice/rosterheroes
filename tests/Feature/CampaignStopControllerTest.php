<?php

namespace Tests\Feature;

use App\Domain\Models\User;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CampaignStopControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_unauthorize_access_to_campaign_stops_for_different_user()
    {
        $campaign = CampaignFactory::new()->create();
        Passport::actingAs(factory(User::class)->create());
        $response = $this->json('GET', 'api/v1/campaigns/' . $campaign->uuid . '/campaign-stops');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_a_campaign_stops_belonging_to_a_campaign()
    {
        $campaign = CampaignFactory::new()->create();
        $campaignStopOne = CampaignStopFactory::new()->withCampaignID($campaign->id)->create();
        $campaignStopTwo = CampaignStopFactory::new()->withCampaignID($campaign->id)->create();

        $diffCampaignStop = CampaignStopFactory::new()
            ->withCampaign(CampaignFactory::new()->withSquadID($campaign->squad_id))->create();

        Passport::actingAs($campaign->squad->user);

        $response = $this->json('GET', 'api/v1/campaigns/' . $campaign->uuid . '/campaign-stops');
        $response->assertStatus(200);
        $this->assertEquals(2, count($response->json('data')));
        $response->assertJson([
            'data' => [
                [
                    'uuid' => $campaignStopOne->uuid,
                ],
                [
                    'uuid' => $campaignStopTwo->uuid,
                ]
            ]
        ]);
    }
}
