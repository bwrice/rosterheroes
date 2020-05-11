<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Actions\SetupNextWeekAction;
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
        $this->clearSpirits->execute();

        $this->setupNextWeekAction->execute();
    }
}
