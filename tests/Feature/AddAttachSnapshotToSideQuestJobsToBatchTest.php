<?php

namespace Tests\Feature;

use App\Domain\Actions\AddAttachSnapshotToSideQuestResultJobsToBatch;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Factories\Models\SquadSnapshotFactory;
use App\Jobs\AttachSnapshotsToSideQuestResultJob;
use App\Jobs\BuildSquadSnapshotJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AddAttachSnapshotToSideQuestJobsToBatchTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AddAttachSnapshotToSideQuestResultJobsToBatch
     */
    protected function getDomainAction()
    {
        return app(AddAttachSnapshotToSideQuestResultJobsToBatch::class);
    }

    /**
     * @test
     */
    public function it_will_add_attach_snapshot_to_side_quest_result_jobs_to_batch()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $sideQuestResults = collect();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);
        for ($i = 1; $i <= rand(3, 10); $i++) {
            $sideQuestResult = $sideQuestResultFactory->create();
            $sideQuestResults->push($sideQuestResult);
        }
        $outOfRangeSideQuestResult = $sideQuestResultFactory->create();

        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $sideQuestResults->first()->id, $sideQuestResults->last()->id);

        Queue::assertPushed(AttachSnapshotsToSideQuestResultJob::class, $sideQuestResults->count());

        foreach ($sideQuestResults as $sideQuestResult) {
            Queue::assertPushed(function (AttachSnapshotsToSideQuestResultJob $job) use ($sideQuestResult) {
                return $job->sideQuestResult->id === $sideQuestResult->id;
            });
        }

        Queue::assertNotPushed(function (AttachSnapshotsToSideQuestResultJob $job) use ($outOfRangeSideQuestResult) {
            return $job->sideQuestResult->id === $outOfRangeSideQuestResult->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_add_jobs_for_side_quest_results_with_snapshots_already_attached()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $sideQuestResults = collect();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);
        for ($i = 1; $i <= 5; $i++) {
            $sideQuestResult = $sideQuestResultFactory->create();
            if ($i == 4) {
                $sideQuestResult->squad_snapshot_id = SquadSnapshotFactory::new()->create()->id;
                $sideQuestResult->side_quest_snapshot_id = SideQuestSnapshotFactory::new()->create()->id;
                $sideQuestResult->save();
                $invalidSideQuestResult = $sideQuestResult;
            }
            $sideQuestResults->push($sideQuestResult);
        }
        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $sideQuestResults->first()->id, $sideQuestResults->last()->id);

        Queue::assertPushed(AttachSnapshotsToSideQuestResultJob::class, $sideQuestResults->count() - 1);

        Queue::assertNotPushed(function (AttachSnapshotsToSideQuestResultJob $job) use ($invalidSideQuestResult) {
            return $job->sideQuestResult->id === $invalidSideQuestResult->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_add_jobs_for_side_quest_results_belonging_to_a_campaign_for_a_non_current_week()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $sideQuestResults = collect();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);
        for ($i = 1; $i <= 5; $i++) {
            if ($i != 4) {
                $sideQuestResult = $sideQuestResultFactory->create();
            } else {
                $invalidCampaignStopFactory = CampaignStopFactory::new()
                    ->withCampaign(CampaignFactory::new()->withWeekID(factory(Week::class)->create()->id));
                $sideQuestResult = $sideQuestResultFactory->withCampaignStop($invalidCampaignStopFactory)->create();
                $invalidSideQuestResult = $sideQuestResult;
            }
            $sideQuestResults->push($sideQuestResult);
        }
        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $sideQuestResults->first()->id, $sideQuestResults->last()->id);

        Queue::assertPushed(AttachSnapshotsToSideQuestResultJob::class, $sideQuestResults->count() - 1);

        Queue::assertNotPushed(function (AttachSnapshotsToSideQuestResultJob $job) use ($invalidSideQuestResult) {
            return $job->sideQuestResult->id === $invalidSideQuestResult->id;
        });
    }
}
