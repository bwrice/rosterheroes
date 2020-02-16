<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Combat\SideQuestGroup;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

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

    /**
     * @param SideQuest $sideQuest
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return SideQuestGroup
     */
    public function execute(SideQuest $sideQuest, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();
        $combatMinions = new CombatantCollection();
        $sideQuest->minions->each(function (Minion $minion) use ($combatMinions, $combatPositions, $targetPriorities, $damageTypes) {
            $combatMinion = $this->buildCombatMinion->execute($minion, $combatPositions, $targetPriorities, $damageTypes);
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
