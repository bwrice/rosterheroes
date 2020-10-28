<?php


namespace App\Domain\Actions\WeekFinalizing;

use App\Domain\Actions\SetupNextWeekAction;

class FinalizeWeekFinalStep implements FinalizeWeekDomainAction
{
    protected SetupNextWeekAction $setupNextWeekAction;
    protected DispatchProcessSideQuestResultJobs $dispatchProcessSideQuestResultJobs;
    protected ClearWeeklyPlayerSpiritsFromHeroes $clearSpirits;

    public function __construct(
        ClearWeeklyPlayerSpiritsFromHeroes $clearSpirits,
        DispatchProcessSideQuestResultJobs $dispatchProcessSideQuestResultJobs,
        SetupNextWeekAction $setupNextWeekAction)
    {
        $this->clearSpirits = $clearSpirits;
        $this->dispatchProcessSideQuestResultJobs = $dispatchProcessSideQuestResultJobs;
        $this->setupNextWeekAction = $setupNextWeekAction;
    }

    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $this->clearSpirits->execute();
        $this->dispatchProcessSideQuestResultJobs->execute();
        $this->setupNextWeekAction->execute();
    }
}
