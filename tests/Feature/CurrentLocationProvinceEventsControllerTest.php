<?php

namespace Tests\Feature;

use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Factories\Models\ProvinceEventFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CurrentLocationProvinceEventsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_province_events_for_the_squads_current_location()
    {
        $count = rand(2, 5);

        $province = Province::query()->inRandomOrder()->first();
        $originalCount = ProvinceEvent::query()->where('province_id', '=', $province->id)->count();

        $eventFactory = ProvinceEventFactory::new()->forProvince($province)->squadEntersProvince();
        for ($i = 1; $i <= $count; $i++) {
            $eventFactory->create();
        }

        $diffProvince = Province::query()->inRandomOrder()->first();
        ProvinceEventFactory::new()->forProvince($diffProvince)->squadEntersProvince()->create();

        $squad = SquadFactory::new()->atProvince($province->id)->create();
        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/current-location/province-events');

        $response->assertStatus(200);
        $this->assertEquals($count + $originalCount, count($response->json('data')));
    }
}
