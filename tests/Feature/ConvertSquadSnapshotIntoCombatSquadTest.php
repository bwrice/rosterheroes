<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertSquadSnapshotIntoCombatSquad;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertSquadSnapshotIntoCombatSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertSquadSnapshotIntoCombatSquad
     */
    protected function getDomainAction()
    {
        return app(ConvertSquadSnapshotIntoCombatSquad::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_squad_with_matching_properties_of_the_squad_snapshot()
    {
        $squadSnapshot = SquadSnapshotFactory::new()->create();
        $combatSquad = $this->getDomainAction()->execute($squadSnapshot);

        $this->assertEquals($squadSnapshot->uuid, $combatSquad->getSourceUuid());
        $this->assertEquals($squadSnapshot->experience, $combatSquad->getExperience());
        $this->assertEquals($squadSnapshot->squad_rank_id, $combatSquad->getSquadRankID());
    }
}
