<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatAttack;
use App\Domain\Combat\CombatHero;
use App\Domain\Combat\CombatItem;
use App\Domain\Combat\SideQuestGroup;
use App\Domain\Combat\CombatSquad;
use Illuminate\Support\Collection;

class RunSideQuestAction
{
    /**
     * @var int
     */
    protected $moment;

    public function __construct()
    {
        $this->moment = 1;
    }

    public function execute(CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $squadAttacks = $this->getSquadAttacks($combatSquad);
        $combatMinions = $sideQuestGroup->getCombatMinions();
        while (true) {

        }
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
        $readyAttacks = $squadAttacks->filter(function (CombatAttack $attack) use ($rand) {
            $speed = $attack->getSpeed();
            return $speed * 10 >= $rand;
        });

        $readyAttacks->each(function (CombatAttack $combatAttack) use ($combatMinions) {
            $this->processAttack($combatAttack, $combatMinions);
        });
    }

    protected function processAttack(CombatAttack $attack, Collection $combatMinions)
    {

    }
}
