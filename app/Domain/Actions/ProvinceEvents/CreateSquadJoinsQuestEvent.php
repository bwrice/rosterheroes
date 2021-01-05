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
use Illuminate\Database\Eloquent\Collection;
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
        /** @var ProvinceEvent|null $provinceEvent */
        $provinceEvent = null;
        ProvinceEvent::query()
            ->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_JOINS_QUEST)
            ->where('province_id', '=', $province->id)
            ->where('squad_id', '=', $squad->id)
            ->chunk(200, function (Collection $provinceEvents) use (&$provinceEvent, $week, $quest) {
                $provinceEvent = $provinceEvents->first(function (ProvinceEvent $provinceEvent) use ($week, $quest) {
                    /** @var SquadJoinsQuestBehavior $behavior */
                    $behavior = $provinceEvent->getBehavior();
                    return $behavior->getQuestUuid() === (string) $quest->uuid &&
                        $behavior->getWeekUuid() === (string) $week->uuid;
                });
                if ($provinceEvent) {
                    return false;
                }
        });

        if ($provinceEvent) {
            $provinceEvent->happened_at = $happenedAt;
            $provinceEvent->save();
        } else {
            /** @var ProvinceEvent $provinceEvent */
            $provinceEvent = ProvinceEvent::query()->create([
                'uuid' => Str::uuid(),
                'event_type' => ProvinceEvent::TYPE_SQUAD_JOINS_QUEST,
                'province_id' => $province->id,
                'squad_id' => $squad->id,
                'extra' => SquadJoinsQuestBehavior::buildExtraArray($quest, $week),
                'happened_at' => $happenedAt
            ]);
        }

        event(new ProvinceEventCreated($provinceEvent));
        return $provinceEvent;
    }
}
