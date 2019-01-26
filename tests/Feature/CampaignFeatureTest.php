<?php

namespace Tests\Feature;

use App\Campaign;
use App\Campaigns\Quests\Quest;
use App\Squad;
use App\Weeks\Week;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function a_campaign_can_add_a_quest()
    {

        /** @var Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id,
            'squad_id' => $squad->id,
            'continent_id' => $squad->province->continent->id
        ]);

        Passport::actingAs($squad->user);

        /** @var Quest $quest */
        $quest = factory(Quest::class)->create([
            'province_id' => $squad->province_id
        ]);

        $response = $this->json('POST', 'api/campaign/' . $campaign->uuid . '/quest/' . $quest->uuid );

        $response->assertStatus(201);
        $this->assertEquals($week->id, $campaign->week_id);
        $this->assertTrue(in_array($quest->id, $campaign->quests()->pluck('id')->toArray()));
        $this->assertEquals($campaign->continent_id, $quest->province->continent_id);
    }

//    /**
//     * @test
//     */
//    public function a_squad_can_join_a_skirmish()
//    {
//        $this->withoutExceptionHandling();
//        $this->fail();
//
//    }
}
