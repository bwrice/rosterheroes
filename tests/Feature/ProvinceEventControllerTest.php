<?php

namespace Tests\Feature;

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
                'uuid' => (string) $provinceEvent->uuid
            ]
        ]);
    }
}
