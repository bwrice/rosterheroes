<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Notifications\BatchCompleted;
use App\Notifications\BatchFailed;
use Illuminate\Bus\Batch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

abstract class BatchedWeeklyAction implements FinalizeWeekDomainAction
{
    protected string $name = '';

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        Bus::batch($this->jobs())->catch(function (Batch $batch, \Throwable $throwable) {
                Admin::notify(new BatchFailed($batch->id, $throwable->getMessage()));
            })->then(function (Batch $batch) use ($finalizeWeekStep) {
                FinalizeWeekJob::dispatch($finalizeWeekStep + 1);
                Admin::notify(new BatchCompleted($batch->id));
            })->name($this->batchName())->dispatch();
    }

    abstract protected function jobs(): Collection;

    protected function batchName()
    {
        $name = $this->name ?: 'Finalize Week Action';
        return $name . " for week: " . CurrentWeek::id();
    }
}
