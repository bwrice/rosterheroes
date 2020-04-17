<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Actions\SetupNextWeekAction;
use App\Facades\CurrentWeek;
use App\Jobs\ProcessSideQuestRewardsJob;
use App\SideQuestResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FinalizeWeekFinalStep implements FinalizeWeekDomainAction
{
    /**
     * @var SetupNextWeekAction
     */
    protected $setupNextWeekAction;
    /**
     * @var ClearWeeklyPlayerSpiritsFromHeroes
     */
    protected $clearSpirits;

    public function __construct(
        ClearWeeklyPlayerSpiritsFromHeroes $clearSpirits,
        SetupNextWeekAction $setupNextWeekAction)
    {
        $this->clearSpirits = $clearSpirits;
        $this->setupNextWeekAction = $setupNextWeekAction;
    }

    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        SideQuestResult::query()->whereHas('campaignStop', function (Builder $builder) {
            return $builder->whereHas('campaign', function (Builder $builder) {
                return $builder->where('week_id' , '=', CurrentWeek::id());
            });
        })->chunk(100, function (Collection $sideQuestResults) {
            $sideQuestResults->each(function (SideQuestResult $sideQuestResult) {
                ProcessSideQuestRewardsJob::dispatch($sideQuestResult);
            });
        });

        $this->clearSpirits->execute();

        $this->setupNextWeekAction->execute();
    }
}
