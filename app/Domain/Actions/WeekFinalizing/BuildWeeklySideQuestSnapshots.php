<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\SideQuest;
use App\Facades\CurrentWeek;
use App\Jobs\BuildSideQuestSnapshotJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BuildWeeklySideQuestSnapshots extends BatchedWeeklyAction
{
    protected string $name = 'Build Side Quest Snapshots';

    protected function jobs(): Collection
    {
        return SideQuest::query()->whereDoesntHave('sideQuestSnapshots', function (Builder $builder) {
            $builder->where('week_id', '=', CurrentWeek::id());
        })->get()->map(function (SideQuest $sideQuest) {
            return new BuildSideQuestSnapshotJob($sideQuest);
        });
    }
}
