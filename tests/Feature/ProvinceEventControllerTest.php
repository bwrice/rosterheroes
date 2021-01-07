<?php

namespace Tests\Feature;

use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\User;
use App\Factories\Models\ProvinceEventFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProvinceEventControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_a_province_event_response()
    {
        $provinceEvent = ProvinceEventFactory::new()->squadEntersProvince()->create();

        Passport::actingAs(factory(User::class)->create());
        $response = $this->json('GET', '/api/v1/province-events/' . $provinceEvent->uuid);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'provinceEvent' => [
                    'uuid' => (string) $provinceEvent->uuid
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_return_global_province_events()
    {
        $localEvent = ProvinceEventFactory::new()->squadEntersProvince()->create();
        $this->assertFalse(in_array($localEvent->event_type, ProvinceEvent::GLOBAL_EVENTS));

        $globalEvent = ProvinceEventFactory::new()->squadJoinsQuest()->create();
        $this->assertTrue(in_array($globalEvent->event_type, ProvinceEvent::GLOBAL_EVENTS));

        Passport::actingAs(factory(User::class)->create());
        $response = $this->json('GET', '/api/v1/province-events');
        $response->assertStatus(200);

        $eventResponses = collect($response->json('data'));

        $matchingLocalEvent = $eventResponses->first(function ($event) use ($localEvent) {
            return $event['uuid'] === (string) $localEvent->uuid;
        });

        $this->assertNull($matchingLocalEvent);

        $matchingGlobLEvent = $eventResponses->first(function ($event) use ($globalEvent) {
            return $event['uuid'] === (string) $globalEvent->uuid;
        });

        $this->assertNotNull($matchingGlobLEvent);
    }
}
