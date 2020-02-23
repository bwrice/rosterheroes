<?php

namespace App\Aggregates;

use App\StorableEvents\SideQuestEventCreated;
use Spatie\EventSourcing\AggregateRoot;

final class SideQuestEventAggregate extends AggregateRoot
{
    public function createSideQuestEvent(int $sideQuestResultID, int $moment, array $data)
    {
        $this->recordThat(new SideQuestEventCreated($sideQuestResultID, $moment, $data));
        return $this;
    }
}
