<?php

namespace App\StorableEvents;

use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\SideQuestResult;
use Spatie\EventSourcing\ShouldBeStored;

final class BattlefieldSetForSideQuest implements ShouldBeStored
{

    /**
     * @var int
     */
    public $sideQuestResultID;
    /**
     * @var array
     */
    public $eventData;

    public function __construct(int $sideQuestResultID, array $eventData)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->eventData = $eventData;
    }
}
