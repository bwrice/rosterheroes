<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertSideQuestSnapshotIntoSideQuestCombatGroup;
use App\Factories\Models\SideQuestSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertSideQuestSnapshotIntoSideQuestCombatGroupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertSideQuestSnapshotIntoSideQuestCombatGroup
     */
    protected function getDomainAction()
    {
        return app(ConvertSideQuestSnapshotIntoSideQuestCombatGroup::class);
    }

    /**
     * @test
     */
    public function it_Will_convert_a_side_quest_snapshot_into_a_combat_group_with_expected_properties()
    {
        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();
        $combatGroup = $this->getDomainAction()->execute($sideQuestSnapshot);

        $this->assertEquals($sideQuestSnapshot->uuid, $combatGroup->getSourceUuid());
    }
}
