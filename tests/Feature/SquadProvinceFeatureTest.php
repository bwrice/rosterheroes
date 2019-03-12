<?php

namespace Tests\Feature;

use App\Province;
use App\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadProvinceFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_squad_can_move_to_province_that_borders_its_own()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Province $border */
        $border = $squad->province->borders->random();

        Passport::actingAs($squad->user);

        $response = $this->json('POST', 'api/squad/' . $squad->uuid . '/border/' . $border->uuid );
        $this->assertEquals(201, $response->status());
        $this->assertEquals($border->id, $squad->fresh()->province_id);
    }
}
