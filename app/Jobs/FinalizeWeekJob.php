<?php

namespace App\Jobs;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekMinionSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekSquadSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekTitanSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekDomainAction;
use App\Domain\Actions\WeekFinalizing\RunCurrentWeekSideQuestsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWeekJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    public $step;

    /**
     * FinalizeWeekJob constructor.
     * @param int $step
     */
    public function __construct(int $step)
    {
        $this->step = $step;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getDomainAction()->execute($this->step);
    }

    protected function getDomainAction(): FinalizeWeekDomainAction
    {
        switch ($this->step) {
            case 1:
                return app(FinalizeCurrentWeekPlayerGameLogsAction::class);
            case 2:
                return app(FinalizeCurrentWeekSpiritEnergiesAction::class);
            case 3:
                return app(BuildCurrentWeekSquadSnapshotsAction::class);
            case 4:
                return app(BuildCurrentWeekMinionSnapshotsAction::class);
            case 5:
                return app(BuildCurrentWeekTitanSnapshotsAction::class);
            case 6:
                return app(RunCurrentWeekSideQuestsAction::class);
            case 7:
                return app(SetupNextWeekAction::class);
        }
        throw new \InvalidArgumentException("Unknown finalize action for step: " . $this->step);
    }
}
