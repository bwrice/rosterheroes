<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use Illuminate\Support\Str;

class CreateSquadJoinsQuestEvent
{
    /**
     * @param Squad $squad
     * @param Quest $quest
     * @param Province $province
     *
     * @return ProvinceEvent
     */
    public function execute(Squad $squad, Quest $quest, Province $province)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => Str::uuid(),
            'event_type' => ProvinceEvent::TYPE_SQUAD_JOINS_QUEST,
            'province_id' => $province->id,
            'squad_id' => $squad->id,
            'extra' => SquadJoinsQuestBehavior::buildExtraArray($quest),
            'happened_at' => now()
        ]);
        return $provinceEvent;
    }
}
