<?php

namespace Tests\Feature;

use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\User;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SquadSnapshotControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_unauthorize_viewing_squad_snapshot_if_not_the_owner_of_the_squad()
    {
        $squadSnapshot = SquadSnapshotFactory::new()->create();

        Passport::actingAs(factory(User::class)->create());
        $response = $this->get('/api/v1/squads/' . $squadSnapshot->squad->slug . '/snapshots/' . $squadSnapshot->week_id);

        $response->assertStatus(403);
    }
}
