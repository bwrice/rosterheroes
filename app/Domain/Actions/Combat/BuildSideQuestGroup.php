<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Combat\SideQuestGroup;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;

class BuildSideQuestGroup
{
    /**
     * @var BuildCombatMinion
     */
    private $buildCombatMinion;

    public function __construct(BuildCombatMinion $buildCombatMinion)
    {
        $this->buildCombatMinion = $buildCombatMinion;
    }

    public function execute(SideQuest $sideQuest)
    {
        $combatMinions = new CombatantCollection();
        $sideQuest->minions->each(function (Minion $minion) use ($combatMinions) {
            $combatMinion = $this->buildCombatMinion->execute($minion);
            $combatMinions->push($combatMinion);
            $minionCount = $minion->pivot->count;
            if ($minionCount > 1) {
                foreach (range(1, $minionCount - 1) as $duplicateMinionCount) {
                    $duplicateMinion = clone $combatMinion;
                    $combatMinions->push($duplicateMinion);
                }
            }
        });

        return new SideQuestGroup($combatMinions);
    }
}
