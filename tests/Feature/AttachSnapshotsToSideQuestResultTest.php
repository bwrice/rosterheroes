<?php

namespace Tests\Feature;

use App\Domain\Actions\AttachSnapshotsToSideQuestResult;
use App\Domain\Models\Week;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttachSnapshotsToSideQuestResultTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AttachSnapshotsToSideQuestResult
     */
    protected function getDomainAction()
    {
        return app(AttachSnapshotsToSideQuestResult::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_squad_snapshot_already_attached()
    {
        $snapshotAlreadyAttached = SquadSnapshotFactory::new()->create();
        $sideQuestResult = SideQuestResultFactory::new()
            ->forSquadSnapshot($snapshotAlreadyAttached->id)
            ->create();

        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(AttachSnapshotsToSideQuestResult::EXCEPTION_SQUAD_SNAPSHOT_ALREADY_ATTACHED, $exception->getCode());
            $this->assertEquals($sideQuestResult->squad_snapshot_id, $snapshotAlreadyAttached->id);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_side_quest_snapshot_already_attached()
    {
        $snapshotAlreadyAttached = SideQuestSnapshotFactory::new()->create();
        $sideQuestResult = SideQuestResultFactory::new()
            ->forSideQuestSnapshot($snapshotAlreadyAttached->id)
            ->create();

        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(AttachSnapshotsToSideQuestResult::EXCEPTION_SIDE_QUEST_SNAPSHOT_ALREADY_ATTACHED, $exception->getCode());
            $this->assertEquals($sideQuestResult->side_quest_snapshot_id, $snapshotAlreadyAttached->id);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_squad_snapshot_for_matching_week_found()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $validSideQuestSnapshot = SideQuestSnapshotFactory::new()
            ->withSideQuestID($sideQuestResult->side_quest_id)
            ->withWeekID($sideQuestResult->campaignStop->campaign->week_id)
            ->create();
        $squadSnapshotForDiffWeek = SquadSnapshotFactory::new()
            ->withSquadID($sideQuestResult->campaignStop->campaign->squad_id)
            ->withWeekID(factory(Week::class)->create()->id)
            ->create();

        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(AttachSnapshotsToSideQuestResult::EXCEPTION_SQUAD_SNAPSHOT_NOT_FOUND, $exception->getCode());
            $this->assertNull($sideQuestResult->squad_snapshot_id);
            $this->assertNull($sideQuestResult->side_quest_snapshot_id);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_side_quest_snapshot_for_matching_week_found()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $validSquadSnapshot = SquadSnapshotFactory::new()
            ->withSquadID($sideQuestResult->campaignStop->campaign->squad_id)
            ->withWeekID($sideQuestResult->campaignStop->campaign->week_id)
            ->create();
        $invalidSideQuestSnapshot = SideQuestSnapshotFactory::new()
            ->withSideQuestID($sideQuestResult->side_quest_id)
            ->withWeekID(factory(Week::class)->create()->id)
            ->create();

        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(AttachSnapshotsToSideQuestResult::EXCEPTION_SIDE_QUEST_SNAPSHOT_NOT_FOUND, $exception->getCode());
            $this->assertNull($sideQuestResult->squad_snapshot_id);
            $this->assertNull($sideQuestResult->side_quest_snapshot_id);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_attach_snapshots_to_the_side_quest()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $validSquadSnapshot = SquadSnapshotFactory::new()
            ->withSquadID($sideQuestResult->campaignStop->campaign->squad_id)
            ->withWeekID($sideQuestResult->campaignStop->campaign->week_id)
            ->create();
        $validSideQuestSnapshot = SideQuestSnapshotFactory::new()
            ->withSideQuestID($sideQuestResult->side_quest_id)
            ->withWeekID($sideQuestResult->campaignStop->campaign->week_id)
            ->create();
        $sideQuestResult = $this->getDomainAction()->execute($sideQuestResult);
        $this->assertEquals($validSquadSnapshot->id, $sideQuestResult->squad_snapshot_id);
        $this->assertEquals($validSideQuestSnapshot->id, $sideQuestResult->side_quest_snapshot_id);
    }
}
