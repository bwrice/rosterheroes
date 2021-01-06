<?php

namespace App\Jobs;

use App\Domain\Actions\ProvinceEvents\CreateSquadLeavesProvinceEvent;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSquadLeavesProvinceEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Province $provinceEntered;
    public Province $provinceLeft;
    public Squad $squad;
    public CarbonInterface $happenedAt;
    public int $gold;

    /**
     * CreateSquadEntersProvinceEventJob constructor.
     * @param Province $provinceEntered
     * @param Province $provinceLeft
     * @param Squad $squad
     * @param CarbonInterface $happenedAt
     */
    public function __construct(
        Province $provinceLeft,
        Province $provinceEntered,
        Squad $squad,
        CarbonInterface $happenedAt)
    {
        $this->provinceLeft = $provinceLeft;
        $this->provinceEntered = $provinceEntered;
        $this->squad = $squad;
        $this->happenedAt = $happenedAt;
    }

    /**
     * @param CreateSquadLeavesProvinceEvent $action
     */
    public function handle(CreateSquadLeavesProvinceEvent $action)
    {
        $action->execute($this->provinceLeft, $this->provinceEntered, $this->squad, $this->happenedAt);
    }
}
