<?php

namespace App\Jobs;

use App\Domain\Actions\ProvinceEvents\CreateSquadEntersProvinceEvent;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSquadEntersProvinceEventJob implements ShouldQueue
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
     * @param int $gold
     */
    public function __construct(
        Province $provinceEntered,
        Province $provinceLeft,
        Squad $squad,
        CarbonInterface $happenedAt,
        int $gold)
    {
        $this->provinceEntered = $provinceEntered;
        $this->provinceLeft = $provinceLeft;
        $this->squad = $squad;
        $this->happenedAt = $happenedAt;
        $this->gold = $gold;
    }

    /**
     * @param CreateSquadEntersProvinceEvent $action
     */
    public function handle(CreateSquadEntersProvinceEvent $action)
    {
        $action->execute($this->provinceEntered, $this->provinceLeft, $this->squad, $this->happenedAt, $this->gold);
    }
}
