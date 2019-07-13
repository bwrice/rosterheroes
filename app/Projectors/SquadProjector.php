<?php

namespace App\Projectors;

use App\Events\SquadCreated;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadCreated(SquadCreated $event, string $aggregateUuid)
    {
        Squad::query()->create([
            'uuid' => $aggregateUuid,
            'user_id' => $event->userID,
            'squad_rank_id' => $event->squadRankID,
            'mobile_storage_rank_id' => $event->mobileStorageRankID,
            'province_id' => $event->provinceID
        ]);
    }
}