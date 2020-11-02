<?php

namespace Tests\Feature;

use App\Domain\Actions\Snapshots\BuildSideQuestSnapshot;
use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\SideQuestSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SideQuestFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildSideQuestSnapshotTest extends BuildWeeklySnapshotTest
{
    use DatabaseTransactions;

    /**
     * @return BuildSideQuestSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildSideQuestSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_build_a_side_quest_snapshot_of_a_side_quest_for_the_current_week()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());
        $sideQuest = SideQuestFactory::new()->create();

        /** @var SideQuestSnapshot $sideQuestSnapshot */
        $sideQuestSnapshot = $this->getDomainAction()->execute($sideQuest);

        $this->assertEquals($currentWeek->id, $sideQuestSnapshot->week_id);
        $this->assertEquals($sideQuest->id, $sideQuestSnapshot->side_quest_id);
        $this->assertEquals($sideQuest->name, $sideQuestSnapshot->name);
        $this->assertEquals($sideQuest->difficulty(), $sideQuestSnapshot->difficulty);
        $this->assertEquals($sideQuest->getExperienceReward(), $sideQuestSnapshot->experience_reward);
        $this->assertEquals($sideQuest->getFavorReward(), $sideQuestSnapshot->favor_reward);
        $this->assertTrue(abs($sideQuest->getExperiencePerMoment() - $sideQuestSnapshot->experience_per_moment) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_side_quest_has_a_minion_with_no_snapshot_for_current_week()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());
        $sideQuest = SideQuestFactory::new()->create();
        $minionWithValidSnapshot = MinionFactory::new()->create();
        MinionSnapshotFactory::new()->withWeekID($currentWeek->id)->withMinionID($minionWithValidSnapshot->id)->create();
        $sideQuest->minions()->save($minionWithValidSnapshot, [
            'count' => 1
        ]);

        $minionMissingSnapshot = MinionFactory::new()->create();
        // Create minion snapshot but for different week
        MinionSnapshotFactory::new()
            ->withWeekID(factory(Week::class)->create()->id)
            ->withMinionID($minionWithValidSnapshot->id)
            ->create();
        $sideQuest->minions()->save($minionMissingSnapshot, [
            'count' => 1
        ]);

        try {
            $this->getDomainAction()->execute($sideQuest);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildSideQuestSnapshot::EXCEPTION_MINION_SNAPSHOT_NOT_FOUND, $exception->getCode());
            $snapshotCreated = SideQuestSnapshot::query()
                ->where('week_id', '=', $currentWeek->id)
                ->where('side_quest_id', '=', $sideQuest->id)
                ->first();
            $this->assertNull($snapshotCreated);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_save_minion_snapshot_of_minions_that_belong_to_the_side_quest_to_the_side_quest_snapshot()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());
        $sideQuest = SideQuestFactory::new()->create();

        for ($i = 1; $i <= rand(2, 4); $i++) {
            $minion = MinionFactory::new()->create();
            MinionSnapshotFactory::new()->withWeekID($currentWeek->id)->withMinionID($minion->id)->create();
            $sideQuest->minions()->save($minion, [
                'count' => rand(1, 3)
            ]);
        }

        $sideQuestMinions = $sideQuest->minions;
        $this->assertTrue($sideQuestMinions->isNotEmpty());

        /** @var SideQuestSnapshot $sideQuestSnapshot */
        $sideQuestSnapshot = $this->getDomainAction()->execute($sideQuest);

        $minionSnapshots = $sideQuestSnapshot->minionSnapshots;
        $this->assertEquals($sideQuestMinions->count(), $minionSnapshots->count());

        $sideQuestMinions->each(function (Minion $minion) use ($minionSnapshots) {
            $matchingSnapshot = $minionSnapshots->first(function (MinionSnapshot $minionSnapshot) use ($minion) {
                return $minion->id === $minionSnapshot->minion_id;
            });
            $this->assertNotNull($matchingSnapshot);
            $this->assertEquals($minion->pivot->count, $matchingSnapshot->pivot->count);
        });
    }
}
