<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\MoveNPCToProvince;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Facades\NPC;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoveNPCToProvinceTest extends NPCActionTest
{

    /**
     * @return MoveNPCToProvince
     */
    protected function getDomainAction()
    {
        return app(MoveNPCToProvince::class);
    }

    /**
     * @test
     */
    public function it_will_move_the_npc_squad_to_a_quests_province_that_is_not_current_location()
    {
        // Use Fetroya continent so no restrictions
        $continentID = Continent::query()->where('name', '=', Continent::FETROYA)->firstOrFail()->id;
        $provinces = Province::query()->where('continent_id', '=', $continentID)->get();

        /** @var Province $squadProvince */
        $squadProvince = $provinces->shuffle()->shift();
        $squad = SquadFactory::new()->atProvince($squadProvince->id)->create();

        /** @var Province $targetProvince */
        $targetProvince = $provinces->shift();
        $this->assertNotEquals($squadProvince->id, $targetProvince->id);

        NPC::shouldReceive('isNPC')->andReturn(true);
        $this->getDomainAction()->execute($squad, $targetProvince);

        $this->assertEquals($targetProvince->id, $squad->fresh()->province_id);
    }

    /**
     * @test
     */
    public function it_will_keep_a_squad_at_its_current_location_if_the_same_as_the_province()
    {
        $squad = SquadFactory::new()->create();
        $currentLocation = $squad->province;

        NPC::shouldReceive('isNPC')->andReturn(true);
        $this->getDomainAction()->execute($squad, $currentLocation);

        $this->assertEquals($currentLocation->id, $squad->fresh()->province_id);
    }
}
