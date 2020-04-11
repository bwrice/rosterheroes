<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatMinion;
use App\Domain\Actions\Combat\BuildSideQuestGroup;
use App\Domain\Combat\CombatGroups\CombatGroup;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Domain\Models\SideQuest;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\SideQuestFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildSideQuestGroupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_a_side_quest_group()
    {
        $minionsOfTypeCount = 3;
        $minionFactory = MinionFactory::new()->setCountForSideQuest($minionsOfTypeCount);
        $minionFactory2 = MinionFactory::new()->setCountForSideQuest($minionsOfTypeCount);
        $sideQuest = SideQuestFactory::new()->withMinions(collect([
            $minionFactory,
            $minionFactory2
        ]))->create();

        $combatMinion = CombatMinionFactory::new()->create();

        $buildCombatMinionMock = \Mockery::mock(BuildCombatMinion::class)
            ->shouldReceive('execute')
            ->andReturn($combatMinion)
            ->getMock();

        app()->instance(BuildCombatMinion::class, $buildCombatMinionMock);

        /** @var BuildSideQuestGroup $domainAction */
        $domainAction = app(BuildSideQuestGroup::class);
        $sideQuestGroup = $domainAction->execute($sideQuest);
        $this->assertTrue($sideQuestGroup instanceof CombatGroup);
        $this->assertTrue($sideQuestGroup instanceof SideQuestGroup);
        $this->assertEquals($minionsOfTypeCount * 2, $sideQuestGroup->getCombatMinions()->count());
    }
}
