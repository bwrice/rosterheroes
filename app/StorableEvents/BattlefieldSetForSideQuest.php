<?php

namespace App\StorableEvents;

use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\SideQuestResult;
use Spatie\EventSourcing\ShouldBeStored;

final class BattlefieldSetForSideQuest extends StorableSideQuestEvent implements ShouldBeStored
{

    public function __construct(int $sideQuestResultID, array $data)
    {
        parent::__construct($sideQuestResultID, 0, $data);
    }
}
