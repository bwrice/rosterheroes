<?php

namespace Tests\Feature;

use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadCampaignControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_campaign_can_add_a_quest()
    {

        /** @var \App\Domain\Models\Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        CarbonImmutable::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var \App\Domain\Models\Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id,
            'squad_id' => $squad->id,
            'continent_id' => $squad->province->continent->id
        ]);

        Passport::actingAs($squad->user);

        /** @var \App\Domain\Models\Quest $quest */
        $quest = factory(Quest::class)->create([
            'province_id' => $squad->province_id
        ]);

        $response = $this->json('POST', 'api/v1/campaign/' . $campaign->uuid . '/quest/' . $quest->uuid );

        $response->assertStatus(201);
        $this->assertEquals($week->id, $campaign->week_id);
        $this->assertTrue(in_array($quest->id, $campaign->quests()->pluck('id')->toArray()));
        $this->assertEquals($campaign->continent_id, $quest->province->continent_id);
    }
}
