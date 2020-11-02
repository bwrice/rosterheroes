<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
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

        if ($this->combatMinionFactories) {
            $combatMinions = $this->combatMinionFactories->map(function (CombatantFactory $combatantFactory) {
                return $combatantFactory->create();
            });
        } else {
            $combatMinions = collect();
        }

        return new SideQuestCombatGroup(
            $sideQuest->uuid,
            $combatMinions
        );
    }

    public function withCombatMinions(Collection $combatMinionFactories = null)
    {
        if (! $combatMinionFactories) {
            $minionCount = rand(1, 5);
            $combatMinionFactories = collect();
            foreach(range(1, $minionCount) as $count) {
                $combatMinionFactories->push(CombatantFactory::new());
            }
        }
        $clone = clone $this;
        $clone->combatMinionFactories = $combatMinionFactories;
        return $clone;
    }
}
