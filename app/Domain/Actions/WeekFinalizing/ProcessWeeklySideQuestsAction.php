<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\SideQuestResult;
use Bwrice\LaravelJobChainGroups\Facades\JobChainGroups;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class ProcessWeeklySideQuestsAction implements FinalizeWeekDomainAction
{
    public const EXTRA_LAST_SIDE_QUEST_RESULT_KEY = 'last_side_quest_result_id';

    protected $maxSideQuestResults = 100;

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
        $sideQuestResults = $this->getSideQuestResults($extra);
        $asyncJobs = $this->getAsyncProcessSideQuestJobs($sideQuestResults);

        $finalizeWeekArgs = $this->getFinalizeWeekArgs($sideQuestResults, $finalizeWeekStep);

        JobChainGroups::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekArgs['step'], $finalizeWeekArgs['extra'])
        ])->onQueue('medium')->dispatch();;
    }

    protected function getSideQuestResults(array $extra)
    {
        $lastSideQuestResultID = array_key_exists(self::EXTRA_LAST_SIDE_QUEST_RESULT_KEY, $extra)
            ? $extra[self::EXTRA_LAST_SIDE_QUEST_RESULT_KEY] : false;

        $query = $this->buildSideQuestResultsQuery($lastSideQuestResultID)->take($this->maxSideQuestResults);

        return $query->get();
    }

    protected function buildSideQuestResultsQuery($lastSideQuestResultID)
    {
        $query = $this->getBaseQuery()->whereHas('campaignStop', function (Builder $builder) {
            $builder->whereHas('campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            });
        })->orderBy('id');

        if ($lastSideQuestResultID) {
            $query->where('id', '>', $lastSideQuestResultID);
        }
        return $query;
    }

    protected function getAsyncProcessSideQuestJobs(Collection $sideQuestResults)
    {
        $jobs = collect();
        $sideQuestResults->map(function (SideQuestResult $sideQuestResult) use ($jobs) {
            $jobs->push($this->getProcessSideQuestResultJob($sideQuestResult));
        });
        return $jobs->toArray();
    }

    protected function getFinalizeWeekArgs(Collection $sideQuestResults, int $currentFinalizeWeekStep)
    {
        if ($this->moreCampaignStopsNeedProcessing($sideQuestResults)) {
            return [
                'step' => $currentFinalizeWeekStep,
                'extra' => [
                    self::EXTRA_LAST_SIDE_QUEST_RESULT_KEY => $sideQuestResults->last()->id
                ]
            ];
        }

        return [
            'step' => $currentFinalizeWeekStep + 1,
            'extra' => []
        ];
    }

    protected function moreCampaignStopsNeedProcessing(Collection $campaignStops)
    {
        return $campaignStops->count() >= $this->maxSideQuestResults
            && $this->buildSideQuestResultsQuery($campaignStops->last()->id)->count() > 0;
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
