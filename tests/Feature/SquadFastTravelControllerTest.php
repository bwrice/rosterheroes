<?php

namespace Tests\Feature;

use App\Domain\Models\Squad;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadFastTravelControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_squad_can_fast_travel_through_multiple_provinces_that_border_each_other()
    {
        $this->withoutExceptionHandling();
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $routeProvinces = collect();
        $currentLocation = $squad->province;

        foreach(range(1,3) as $count) {
            $border = $currentLocation->borders()->inRandomOrder()->first();
            $routeProvinces[$count - 1] = $border;
            $currentLocation = $border;
        }

        $finalLocation = $currentLocation;

        Passport::actingAs($squad->user);

        $this->assertEquals(3, $routeProvinces->count());

        $response = $this->post('/api/v1/squads/' . $squad->uuid . '/fast-travel', [
            'travel_route' => $routeProvinces->pluck('uuid')->toArray()
        ]);

        $response->assertStatus(201);

        $squad = $squad->fresh();

        $this->assertEquals($finalLocation->id, $squad->province_id);
    }
}
