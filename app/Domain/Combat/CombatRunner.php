<?php


namespace App\Domain\Combat;


class CombatRunner
{
    /**
     * @var CombatGroup
     */
    private $defenders;
    /**
     * @var CombatGroup
     */
    private $attackers;

    public function __construct(CombatGroup $defenders, CombatGroup $attackers)
    {
        $this->defenders = $defenders;
        $this->attackers = $attackers;
    }

    public function run()
    {
        $moment = 1;
        while($moment <= 5000) {

            $combatActions = $this->defenders->getCombatActions($moment);
            $combatActions->each(function (CombatAction $combatAction) {

            });
            $moment++;
        }
    }
}
