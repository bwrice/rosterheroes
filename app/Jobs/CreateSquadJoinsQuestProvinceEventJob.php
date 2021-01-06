<?php

namespace App\Jobs;

use App\Domain\Actions\ProvinceEvents\CreateSquadJoinsQuestEvent;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSquadJoinsQuestProvinceEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public Squad $squad;
    public Quest $quest;
    public Province $province;
    public Week $week;
    public CarbonInterface $happenedAt;

    /**
     * CreateSquadJoinsQuestProvinceEventJob constructor.
     * @param Squad $squad
     * @param Quest $quest
     * @param Province $province
     * @param Week $week
     * @param CarbonInterface $happenedAt
     */
    public function __construct(
        Squad $squad,
        Quest $quest,
        Province $province,
        Week $week,
        CarbonInterface $happenedAt
    ){
        $this->squad = $squad;
        $this->quest = $quest;
        $this->province = $province;
        $this->week = $week;
        $this->happenedAt = $happenedAt;
    }

    /**
     * @param CreateSquadJoinsQuestEvent $createSquadJoinsQuestEvent
     */
    public function handle(CreateSquadJoinsQuestEvent $createSquadJoinsQuestEvent)
    {
        $createSquadJoinsQuestEvent->execute($this->squad, $this->quest, $this->province, $this->week, $this->happenedAt);
    }
}
