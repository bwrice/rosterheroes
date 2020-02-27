<?php


namespace App\Factories\Combat;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Factories\Models\SideQuestFactory;
use Illuminate\Support\Collection;

class SideQuestGroupFactory
{
    /** @var SideQuestFactory */
    protected $sideQuestFactory;

    /** @var Collection */
    protected $combatMinionFactories;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $sideQuestFactory = $this->sideQuestFactory ?: SideQuestFactory::new();
        $sideQuest = $sideQuestFactory->create();
        $combatMinions = $this->combatMinionFactories->map(function (CombatMinionFactory $combatMinionFactory) {
            return $combatMinionFactory->create();
        });
        return new SideQuestGroup(
            $sideQuest->name,
            $sideQuest->uuid,
            new AbstractCombatantCollection($combatMinions)
        );
    }
}
