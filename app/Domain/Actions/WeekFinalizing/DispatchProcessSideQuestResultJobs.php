<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\SideQuestResult;
use App\Facades\CurrentWeek;
use App\Jobs\ProcessCombatForSideQuestResultJob;
use App\Jobs\ProcessSideQuestRewardsJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;

class DispatchProcessSideQuestResultJobs
{
    public function execute()
    {
        SideQuestResult::query()
            ->whereNull(['combat_processed_at'])
            ->whereHas('campaignStop.campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            })->chunk(100, function (Collection $sideQuestResults) {
                $sideQuestResults->each(function (SideQuestResult $sideQuestResult) {
                    Bus::chain([
                        new ProcessCombatForSideQuestResultJob($sideQuestResult),
                        new ProcessSideQuestRewardsJob($sideQuestResult)
                        // TODO: Process Side Effects
                    ])->onQueue('long')->dispatch();
                });
            });
    }
}
