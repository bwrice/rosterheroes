<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SideQuestEventCreated implements ShouldBeStored
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
    public $data;

    public function __construct(int $sideQuestResultID, int $moment, array $data)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->data = $data;
    }
}
