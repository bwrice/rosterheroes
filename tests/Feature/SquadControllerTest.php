<?php

namespace Tests\Feature;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_new_squad_can_be_created()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs(factory(User::class)->create());

        $name = 'TestSquad' . rand(1,999999);

        $response = $this->json('POST','api/v1/squads', [
           'name' => $name
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'name' => $name
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_squad_can_create_a_campaign()
    {
        $this->withoutExceptionHandling();

        /** @var \App\Domain\Models\Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        CarbonImmutable::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var User $user */
        $user = Passport::actingAs($squad->user);

        $response = $this->json('POST','api/v1/squad/' . $squad->slug . '/campaigns');
        $response->assertStatus(201);

        /** @var \App\Domain\Models\Campaign $campaign */
        $campaign = $squad->campaigns()->first();
        $this->assertEquals($squad->id, $campaign->squad_id);
        $this->assertEquals($week->id, $campaign->week_id);
        $this->assertEquals($squad->province->continent_id, $campaign->continent_id);
    }
}
