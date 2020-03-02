<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SideQuestDefeat implements ShouldBeStored
{
    /**
     * @var int
     */
    public $sideQuestResultID;
    /**
     * @var int
     */
    public $moment;
    /**
     * @var array
     */
    public $eventData;

    public function __construct(int $sideQuestResultID, int $moment, array $eventData)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->eventData = $eventData;
    }
}
