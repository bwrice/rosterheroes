<?php

namespace Tests\Feature;

use App\Domain\Models\Campaign;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CampaignHistoryControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_a_squads_historic_campaigns_not_including_current_campaign()
    {
        $this->withoutExceptionHandling();

        /** @var Week $previousWeek */
        $previousWeek = factory(Week::class)->create();
        $squad = SquadFactory::new()->create();

        $historicCampaign = CampaignFactory::new()->withSquadID($squad->id)->withWeekID($previousWeek->id)->create();

        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();

        /** Make a campaign for the current week to assert its not there */
        CampaignFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/campaign-history');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(1, count($data));

        $response->assertJson([
            'data' => [
                [
                    'uuid' => (string) $historicCampaign->uuid
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_return_a_paginated_response_of_historic_campaigns_ordered_by_most_recent_week()
    {
        $this->withoutExceptionHandling();

        $squad = SquadFactory::new()->create();
        $campaignFactory = CampaignFactory::new()->withSquadID($squad->id);

        $expectedPaginatedCampaigns = collect();

        $perPage = (new Campaign())->getPerPage();
        for ($i = 1; $i <= $perPage + 2; $i++) {
            /** @var Week $previousWeek */
            $previousWeek = factory(Week::class)->create();
            $previousWeek->adventuring_locks_at = Date::now()->subWeeks($i);
            $previousWeek->save();
            $campaign = $campaignFactory->withWeekID($previousWeek->id)->create();
            if ($i <= 2) {
                // The earlier campaigns will be on the next page
                $expectedPaginatedCampaigns->push($campaign);
            }
        }

        $currentWeek = factory(Week::class)->states('as-current')->create();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/campaign-history?page=2');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(2, count($data));

        $response->assertJson([
            'data' => $expectedPaginatedCampaigns->reverse()->map(function (Campaign $campaign) {
                return [
                    'uuid' => $campaign->uuid
                ];
            })->values()->toArray()
        ]);
    }
}
