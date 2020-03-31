<?php

namespace App\Jobs;

use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekDomainAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekFinalStep;
use App\Domain\Actions\WeekFinalizing\ProcessCurrentWeekSideQuestsAction;
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
                return app(ProcessCurrentWeekSideQuestsAction::class);
            case 4:
                return app(FinalizeWeekFinalStep::class);
        }
        throw new \InvalidArgumentException("Unknown finalize action for step: " . $this->step);
    }
}
