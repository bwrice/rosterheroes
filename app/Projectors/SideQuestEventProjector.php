<?php

namespace App\Projectors;

use App\SideQuestEvent;
use App\StorableEvents\SideQuestEventCreated;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class SideQuestEventProjector implements Projector
{
    use ProjectsEvents;

    public function onEventHappened(SideQuestEventCreated $event, string $aggregateUuid)
    {
        SideQuestEvent::query()->create([
            'uuid' => $aggregateUuid,
            'side_quest_result_id' => $event->sideQuestResultID,
            'moment' => $event->moment,
            'data' => $event->data
        ]);
    }
}
