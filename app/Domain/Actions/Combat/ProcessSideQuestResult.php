<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;

class ProcessSideQuestResult
{
    /**
     * @var BuildCombatSquadAction
     */
    private $buildCombatSquadAction;

    public function __construct(BuildCombatSquadAction $buildCombatSquadAction)
    {
        $this->buildCombatSquadAction = $buildCombatSquadAction;
    }

    public function execute(Squad $squad, SideQuest $sideQuest)
    {
        $combatSquad = $this->buildCombatSquadAction->execute($squad);
    }
}
