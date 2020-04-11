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

class ProcessCurrentWeekSideQuestsAction implements FinalizeWeekDomainAction
{
    protected $maxCampaignStopsQueried = 100;

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $campaignStops = $this->getCampaignStops($extra);
        $asyncJobs = $this->getProcessCombatJobs($campaignStops);

        $finalizeWeekArgs = $this->getFinalizeWeekArgs($campaignStops, $finalizeWeekStep);

        ChainGroup::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekArgs['step'], $finalizeWeekArgs['extra'])
        ])->dispatch();;
    }

    protected function getCampaignStops(array $extra)
    {
        $lastCampaignStopID = array_key_exists('last_campaign_stop_id', $extra)
            ? $extra['last_campaign_stop_id'] : false;

        $query = $this->buildCampaignStopsQuery($lastCampaignStopID)->take($this->maxCampaignStopsQueried);

        return $query->get();
    }

    protected function buildCampaignStopsQuery($lastCampaignStopID)
    {
        $query = CampaignStop::query()
            ->whereHas('campaign', function (Builder $builder) {
                return $builder->where('week_id', '=', CurrentWeek::id());
            })
            ->orderBy('id')
            ->with('sideQuestResults');

        if ($lastCampaignStopID) {
            $query->where('id', '>', $lastCampaignStopID);
        }
        return $query;
    }

    protected function getProcessCombatJobs(Collection $campaignStops)
    {
        $jobs = collect();
        $campaignStops->map(function (CampaignStop $campaignStop) use ($jobs) {
            $campaignStop->sideQuestResults->each(function (SideQuestResult $sideQuestResult) use ($jobs) {
                $jobs->push(new ProcessCombatForSideQuestResultJob($sideQuestResult));
            });
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
            && $this->buildCampaignStopsQuery($campaignStops->last()->id)->count() > 0;
    }

    /**
     * @param int $maxCampaignStopsQueried
     * @return ProcessCurrentWeekSideQuestsAction
     */
    public function setMaxCampaignStopsQueried(int $maxCampaignStopsQueried): ProcessCurrentWeekSideQuestsAction
    {
        $this->maxCampaignStopsQueried = $maxCampaignStopsQueried;
        return $this;
    }
}
