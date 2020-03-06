<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\ProcessSideQuestResultJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProcessCurrentWeekSideQuestsAction implements FinalizeWeekDomainAction
{
    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        ChainGroup::create($this->getProcessResultJobs(), [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ])->dispatch();;
    }

    protected function getProcessResultJobs()
    {
        $jobs = collect();
        CampaignStop::query()
            ->whereHas('campaign', function (Builder $builder) {
                return $builder->where('week_id', '=', CurrentWeek::id());
            })
            ->orderBy('id')
            ->with('sideQuests')
            ->get()
            ->map(function (CampaignStop $campaignStop) use ($jobs) {
            $campaignStop->sideQuests->each(function (SideQuest $sideQuest) use ($campaignStop, $jobs) {
                $jobs->push(new ProcessSideQuestResultJob($campaignStop, $sideQuest));
            });
        });
        return $jobs->toArray();
    }
}
