<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertHeroSnapshotIntoCombatant;
use App\Domain\Actions\ConvertMinionSnapshotIntoCombatant;
use App\Domain\Actions\ConvertSideQuestSnapshotIntoSideQuestCombatGroup;
use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\MinionSnapshot;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Factories\Models\SquadSnapshotFactory;
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
    public function it_will_convert_a_side_quest_snapshot_into_a_combat_group_with_expected_properties()
    {
        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();
        $combatGroup = $this->getDomainAction()->execute($sideQuestSnapshot);

        $this->assertEquals($sideQuestSnapshot->uuid, $combatGroup->getSourceUuid());
    }

    /**
     * @test
     */
    public function it_will_execute_convert_combat_minion_for_each_minion_snapshot_belonging_to_the_side_quest_snapshot()
    {
        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();

        $minionSnapshots = collect();
        $minionSnapshotFactory = MinionSnapshotFactory::new();
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $minionSnapshot = $minionSnapshotFactory->create();
            $count = rand(1, 3);
            $sideQuestSnapshot->minionSnapshots()->save($minionSnapshot, [
                'count' => $count
            ]);
            for ($j = 1; $j <= $count; $j++) {
                $minionSnapshots->push($minionSnapshot);
            }
        }

        $this->assertGreaterThan(1, $minionSnapshots->count());

        $minionSnapshotUuids = $minionSnapshots->map(function (MinionSnapshot $minionSnapshot) {
            return $minionSnapshot->uuid;
        });

        $convertCombatHeroMock = $this->getMockBuilder(ConvertMinionSnapshotIntoCombatant::class)
            ->disableOriginalConstructor()
            ->getMock();

        $convertCombatHeroMock->expects($this->exactly($minionSnapshots->count()))
            ->method('execute')
            ->with($this->callback(function (MinionSnapshot $minionSnapshot) use ($minionSnapshotUuids) {
                $matchingKey = $minionSnapshotUuids->search($minionSnapshot->uuid);
                if ($matchingKey === false) {
                    return false;
                }
                $minionSnapshotUuids->forget($matchingKey);
                return true;
            }))
            ->willReturn('anything');

        $this->instance(ConvertMinionSnapshotIntoCombatant::class, $convertCombatHeroMock);

        $sideQuestCombatGroup = $this->getDomainAction()->execute($sideQuestSnapshot);
        $this->assertEquals($minionSnapshots->count(), $sideQuestCombatGroup->getCombatMinions()->count());
    }
}
