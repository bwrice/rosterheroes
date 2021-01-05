<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Events\ProvinceEventCreated;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class CreateSquadJoinsQuestEvent
{
    /**
     * @param Squad $squad
     * @param Quest $quest
     * @param Province $province
     * @param Week $week
     * @param CarbonInterface $happenedAt
     *
     * @return ProvinceEvent
     */
    public function execute(Squad $squad, Quest $quest, Province $province, Week $week, CarbonInterface $happenedAt)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => Str::uuid(),
            'event_type' => ProvinceEvent::TYPE_SQUAD_JOINS_QUEST,
            'province_id' => $province->id,
            'squad_id' => $squad->id,
            'extra' => SquadJoinsQuestBehavior::buildExtraArray($quest, $week),
            'happened_at' => $happenedAt
        ]);

        event(new ProvinceEventCreated($provinceEvent));
        return $provinceEvent;
    }
}
