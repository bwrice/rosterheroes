<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Facades\CurrentWeek;
use App\Jobs\ProcessSideQuestRewardsJob;
use App\Domain\Models\SideQuestResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;

class ProcessWeeklySideQuestRewards extends ProcessWeeklySideQuestsAction
{
    protected function getBaseQuery(): Builder
    {
        return SideQuestResult::query()
            ->whereNotNull('combat_processed_at')
            ->whereNull('rewards_processed_at');
    }

    protected function getProcessSideQuestResultJob(SideQuestResult $sideQuestResult): ShouldQueue
    {
        return new ProcessSideQuestRewardsJob($sideQuestResult);
    }

    /**
     * @throws \Exception
     */
    protected function validateReady()
    {
        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Week must be finalizing to process side quest combat");
        }

        $unprocessedCombatCount = SideQuestResult::query()
            ->whereNull('combat_processed_at')->whereHas('campaignStop', function (Builder $builder) {
                $builder->whereHas('campaign', function (Builder $builder) {
                    $builder->where('week_id', '=', CurrentWeek::id());
                });
            })->count();

        if ($unprocessedCombatCount > 0) {
            throw new \Exception("There are " . $unprocessedCombatCount . " side quest results without combat processed");
        }
    }
}
