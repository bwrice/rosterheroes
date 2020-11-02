<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Minion;
use App\Facades\CurrentWeek;
use App\Jobs\BuildMinionSnapshotJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BuildWeeklyMinionSnapshots extends BatchedWeeklyAction
{
    protected string $name = 'Build Minion Snapshots';

    protected function jobs(): Collection
    {
        return Minion::query()->whereDoesntHave('minionSnapshots', function (Builder $builder) {
            $builder->where('week_id', '=', CurrentWeek::id());
        })->get()->map(function (Minion $minion) {
            return new BuildMinionSnapshotJob($minion);
        });
    }
}
