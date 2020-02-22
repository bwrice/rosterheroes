<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\CombatItem;
use App\Domain\Combat\SideQuestGroup;
use App\Domain\Combat\CombatSquad;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class RunSideQuestAction
{
    /** @var int */
    protected $moment;

    /** @var Collection */
    protected $combatPositions;

    /** @var Collection */
    protected $combatHeroes;

    public function __construct()
    {
        $this->moment = 1;
    }

    public function execute(CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $this->combatPositions = CombatPosition::all();
        $this->combatHeroes = $combatSquad->getCombatHeroes();
        $squadAttacks = $this->getSquadAttacks($combatSquad);
        $combatMinions = $sideQuestGroup->getCombatMinions();
        while (true) {
            // update combat positions on heroes
            // filter attacks by validity
            // filter attacks by randomized speed
            // loop through each attack
            // filter minions by combat position
            // or next combat position rank if none found
            // sort minions by target priority
            // apply attacks to minions
            // check if any survivors
        }
    }

    protected function updateCombatHeroCombatPositions()
    {

    }

    protected function getSquadAttacks(CombatSquad $combatSquad)
    {
        $attacks = collect();
        $combatSquad->getCombatHeroes()->each(function (CombatHero $combatHero) use (&$attacks) {
            $combatHero->getCombatItems()->each(function (CombatItem $combatItem) use (&$attacks) {
                $attacks = $attacks->merge($combatItem->getCombatAttacks());
            });
        });
        return $attacks;
    }

    protected function processSquadTurn(Collection $squadAttacks, Collection $combatMinions)
    {
        $rand = random_int(1, 1000);
        $readyAttacks = $squadAttacks->filter(function (CombatAttackInterface $attack) use ($rand) {
            $speed = $attack->getSpeed();
            return $speed * 10 >= $rand;
        });

        $readyAttacks->each(function (CombatAttackInterface $combatAttack) use ($combatMinions) {
            $this->processAttack($combatAttack, $combatMinions);
        });
    }

    protected function processAttack(CombatAttackInterface $attack, Collection $combatMinions)
    {

    }
}
