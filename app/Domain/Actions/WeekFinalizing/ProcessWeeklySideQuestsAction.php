<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Domain\Models\SideQuestResult;
use Bwrice\LaravelJobChainGroups\Facades\JobChainGroups;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

abstract class ProcessWeeklySideQuestsAction implements FinalizeWeekDomainAction
{
    public const EXTRA_LAST_SIDE_QUEST_RESULT_KEY = 'last_side_quest_result_id';
    public const EXTRA_LAST_CYCLE_COUNT_KEY = 'last_cycle_count';

    protected $maxSideQuestResults = 500;

    abstract protected function getBaseQuery(): Builder;

    abstract protected function getProcessSideQuestResultJob(SideQuestResult $sideQuestResult): ShouldQueue;

    abstract protected function validateReady();

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $this->validateReady();
        $asyncJobs = $this->getAsyncProcessSideQuestJobs();

        $finalGroupToProcess = $asyncJobs->count() < $this->maxSideQuestResults;

        $cycleCount = array_key_exists(self::EXTRA_LAST_CYCLE_COUNT_KEY, $extra)
            ? $extra[self::EXTRA_LAST_CYCLE_COUNT_KEY]++ : 1;

        if ($finalGroupToProcess) {
            $finalizeWeekStep++;
            $extra = [];
        } else {
            $extra[self::EXTRA_LAST_CYCLE_COUNT_KEY] = $cycleCount;
        }

        Log::alert("Dispatching " . $asyncJobs->count() . " jobs on cycle " . $cycleCount . " of " . static::class);

        JobChainGroups::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekStep, $extra)
        ])->onQueue('medium')->dispatch();
    }

    protected function buildSideQuestResultsQuery()
    {
        $query = $this->getBaseQuery()->whereHas('campaignStop', function (Builder $builder) {
            $builder->whereHas('campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            });
        });
        return $query;
    }

    protected function getAsyncProcessSideQuestJobs()
    {
        return $this->buildSideQuestResultsQuery()
            ->take($this->maxSideQuestResults)
            ->get()->map(function (SideQuestResult $sideQuestResult) {
             return $this->getProcessSideQuestResultJob($sideQuestResult);
        });
    }

    /**
     * @param int $maxSideQuestResults
     * @return static
     */
    public function setMaxSideQuestResults(int $maxSideQuestResults)
    {
        $this->maxSideQuestResults = $maxSideQuestResults;
        return $this;
    }
}
