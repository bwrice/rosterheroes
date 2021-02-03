<?php

namespace App\Jobs;

use App\Domain\Actions\RaiseMeasurableAction;
use App\Domain\Models\Measurable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RaiseMeasurableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Measurable $measurable;
    public int $amount;

    /**
     * RaiseMeasurableJob constructor.
     * @param Measurable $measurable
     * @param int $amount
     */
    public function __construct(Measurable $measurable, int $amount)
    {
        $this->measurable = $measurable;
        $this->amount = $amount;
    }

    /**
     * @param RaiseMeasurableAction $raiseMeasurable
     * @throws \App\Exceptions\RaiseMeasurableException
     */
    public function handle(RaiseMeasurableAction $raiseMeasurable)
    {
        $raiseMeasurable->execute($this->measurable, $this->amount);
    }
}
