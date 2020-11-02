<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\BuildWeeklySquadSnapshots;
use App\Domain\Models\Squad;
use App\Factories\Models\SquadFactory;
use App\Jobs\BuildSquadSnapshotsForGroupJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklySquadSnapshotsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildWeeklySquadSnapshots
     */
    protected function getDomainAction()
    {
        return app(BuildWeeklySquadSnapshots::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_build_squad_snapshots_for_group_jobs()
    {
        for ($i = 1; $i <= rand(10, 20); $i++) {
            SquadFactory::new()->create();
        }

        $groupSize = rand(4, 8);
        $expectedRanges = collect();
        Squad::query()->orderBy('id')->select(['id'])->chunk($groupSize, function (Collection $squads) use ($expectedRanges) {
            $expectedRanges->push([
                'startRangeID' => $squads->first()->id,
                'endRangeID' => $squads->last()->id
            ]);
        });

        Queue::fake();

        $this->getDomainAction()->setGroupSize($groupSize)->execute(1);

        Queue::assertPushed(BuildSquadSnapshotsForGroupJob::class, $expectedRanges->count());

        $expectedRanges->each(function ($range) {
            Queue::assertPushed(function (BuildSquadSnapshotsForGroupJob $job) use ($range) {
                return $job->startRangeID == $range['startRangeID'] && $job->endRangeID == $range['endRangeID'];
            });
        });
    }
}
