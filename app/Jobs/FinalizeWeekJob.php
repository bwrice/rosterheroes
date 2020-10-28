<?php

namespace App\Jobs;

use App\Domain\Actions\WeekFinalizing\AttachWeeklySnapshotsToSideQuestResults;
use App\Domain\Actions\WeekFinalizing\BuildWeeklyMinionSnapshots;
use App\Domain\Actions\WeekFinalizing\BuildWeeklySideQuestSnapshots;
use App\Domain\Actions\WeekFinalizing\BuildWeeklySquadSnapshots;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekDomainAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekFinalStep;
use App\Domain\Actions\WeekFinalizing\SetupAllQuestsForNextWeek;
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
     * @var array
     */
    public $extra;

    /**
     * FinalizeWeekJob constructor.
     * @param int $step
     * @param array $extra
     */
    public function __construct(int $step, array $extra = [])
    {
        $this->step = $step;
        $this->extra = $extra;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getDomainAction()->execute($this->step, $this->extra);
    }

    protected function getDomainAction(): FinalizeWeekDomainAction
    {
        switch ($this->step) {
            case 1:
                return app(FinalizeCurrentWeekPlayerGameLogsAction::class);
            case 2:
                return app(FinalizeCurrentWeekSpiritEnergiesAction::class);
            case 3:
                return app(BuildWeeklyMinionSnapshots::class);
            case 4:
                return app(BuildWeeklySideQuestSnapshots::class);
            case 5:
                return app(BuildWeeklySquadSnapshots::class);
            case 6:
                return app(AttachWeeklySnapshotsToSideQuestResults::class);
            case 7:
                return app(SetupAllQuestsForNextWeek::class);
            case 8:
                return app(FinalizeWeekFinalStep::class);
        }
        throw new \InvalidArgumentException("Unknown finalize action for step: " . $this->step);
    }
}
