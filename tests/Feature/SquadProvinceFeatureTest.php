<?php

namespace Tests\Feature;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
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
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Province $border */
        $border = $squad->province->borders->random();

        Sanctum::actingAs($squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $squad->slug . '/border-travel/' . $border->slug );
        $this->assertEquals(201, $response->status());
        $this->assertEquals($border->id, $squad->fresh()->province_id);
    }
}
