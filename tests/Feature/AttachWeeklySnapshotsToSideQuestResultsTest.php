<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\AttachWeeklySnapshotsToSideQuestResults;
use App\Domain\Actions\WeekFinalizing\BuildWeeklySquadSnapshots;
use App\Domain\Models\SideQuestResult;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\AttachSnapshotsToSideQuestResultsForGroupJob;
use App\Jobs\BuildSquadSnapshotsForGroupJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AttachWeeklySnapshotsToSideQuestResultsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AttachWeeklySnapshotsToSideQuestResults
     */
    protected function getDomainAction()
    {
        return app(AttachWeeklySnapshotsToSideQuestResults::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_attach_snapshots_to_side_quest_results_for_group_jobs()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignStopFactory = CampaignStopFactory::new()
            ->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);
        for ($i = 1; $i <= rand(10, 20); $i++) {
            $sideQuestResultFactory->create();
        }

        $groupSize = rand(4, 8);
        $expectedRanges = collect();
        SideQuestResult::query()->whereHas('campaignStop', function (Builder $builder) {
            $builder->whereHas('campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            });
        })->orderBy('id')->select(['id'])->chunk($groupSize, function (Collection $squads) use ($expectedRanges) {
            $expectedRanges->push([
                'startRangeID' => $squads->first()->id,
                'endRangeID' => $squads->last()->id
            ]);
        });

        Queue::fake();

        $this->getDomainAction()->setGroupSize($groupSize)->execute(1);

        Queue::assertPushed(AttachSnapshotsToSideQuestResultsForGroupJob::class, $expectedRanges->count());

        $expectedRanges->each(function ($range) {
            Queue::assertPushed(function (AttachSnapshotsToSideQuestResultsForGroupJob $job) use ($range) {
                return $job->startRangeID == $range['startRangeID'] && $job->endRangeID == $range['endRangeID'];
            });
        });
    }
}
