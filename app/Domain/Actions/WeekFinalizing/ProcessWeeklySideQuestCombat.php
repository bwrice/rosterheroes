<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\ProcessCombatForSideQuestResultJob;
use App\SideQuestResult;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProcessWeeklySideQuestCombat implements FinalizeWeekDomainAction
{
    protected $maxCampaignStopsQueried = 100;

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $sideQuestResults = $this->getSideQuestResults($extra);
        $asyncJobs = $this->getProcessCombatJobs($sideQuestResults);

        $finalizeWeekArgs = $this->getFinalizeWeekArgs($sideQuestResults, $finalizeWeekStep);

        ChainGroup::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekArgs['step'], $finalizeWeekArgs['extra'])
        ])->onQueue('medium')->dispatch();;
    }

    protected function getSideQuestResults(array $extra)
    {
        $lastSideQuestResultID = array_key_exists('last_campaign_stop_id', $extra)
            ? $extra['last_campaign_stop_id'] : false;

        $query = $this->buildSideQuestResultsQuery($lastSideQuestResultID)->take($this->maxCampaignStopsQueried);

        return $query->get();
    }

    protected function buildSideQuestResultsQuery($lastSideQuestResultID)
    {
        $query = SideQuestResult::query()->whereNull('combat_processed_at')->whereHas('campaignStop', function (Builder $builder) {
            $builder->whereHas('campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            });
        })->orderBy('id');

        if ($lastSideQuestResultID) {
            $query->where('id', '>', $lastSideQuestResultID);
        }
        return $query;
    }

    protected function getProcessCombatJobs(Collection $sideQuestResults)
    {
        $jobs = collect();
        $sideQuestResults->map(function (SideQuestResult $sideQuestResult) use ($jobs) {
            $jobs->push(new ProcessCombatForSideQuestResultJob($sideQuestResult));
        });
        return $jobs->toArray();
    }

    protected function getFinalizeWeekArgs(Collection $campaignStops, int $currentFinalizeWeekStep)
    {
        if ($this->moreCampaignStopsNeedProcessing($campaignStops)) {
            return [
                'step' => $currentFinalizeWeekStep,
                'extra' => [
                    'last_campaign_stop_id' => $campaignStops->last()->id
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
        return $campaignStops->count() >= $this->maxCampaignStopsQueried
            && $this->buildSideQuestResultsQuery($campaignStops->last()->id)->count() > 0;
    }

    /**
     * @param int $maxCampaignStopsQueried
     * @return ProcessWeeklySideQuestCombat
     */
    public function setMaxCampaignStopsQueried(int $maxCampaignStopsQueried): ProcessWeeklySideQuestCombat
    {
        $this->maxCampaignStopsQueried = $maxCampaignStopsQueried;
        return $this;
    }
}
