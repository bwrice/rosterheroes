<?php

namespace Tests\Feature;

use App\Campaign;
use App\Quest;
use App\Squad;
use App\Weeks\Week;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadQuestFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function a_squad_can_join_a_quest()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        Passport::actingAs($squad->user);

        /** @var Quest $quest */
        $quest = factory(Quest::class)->create();

        /** @var Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        $response = $this->json('POST', 'api/squad/' . $squad->uuid . '/quest/' . $quest->uuid );

        $response->assertStatus(201);
        $this->assertEquals(1, $squad->campaigns()->count() );

        /** @var Campaign $campaign */
        $campaign = $squad->campaigns()->first();

        $this->assertEquals($week->id, $campaign->week_id);
        $this->assertEquals($campaign->continent_id, $quest->province->continent_id);
    }
}
