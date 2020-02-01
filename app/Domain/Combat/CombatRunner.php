<?php


namespace App\Domain\Combat;


use Illuminate\Support\Collection;

class CombatRunner
{
    /**
     * @var Collection
     */
    protected $eventHandlers;
    /**
     * @var CombatMoment
     */
    private $combatMoment;

    public function __construct(CombatMoment $combatMoment)
    {
        $this->eventHandlers = collect();
        $this->combatMoment = $combatMoment;
    }

    public function execute(CombatGroup $sideA, CombatGroup $sideB)
    {
        while(true) {

            $this->combatMoment->setSideA();
            $defeated = $this->handleSingleSide($sideA, $sideB);

            if ($defeated) {
                //TODO: handle combat finished
                continue;
            }

            $this->combatMoment->setSideB();
            $defeated = $this->handleSingleSide($sideB, $sideA);

            if ($defeated) {
                //TODO: handle combat finished
                continue;
            }

            $this->combatMoment->tick();
        }
    }

    protected function handleSingleSide(CombatGroup $attackers, CombatGroup $defenders)
    {
        $combatActions = $attackers->getCombatActions($this->combatMoment->getCount());

        $combatActions->each(function (CombatAttackInterface $combatAttack) use ($defenders) {

            if (! $defenders->isDefeated($this->combatMoment)) {
                $combatEvents = $defenders->receiveAttack($combatAttack);
                $combatEvents->each(function (CombatEvent $combatEvent) {
                    $this->notifyEventHandlers($combatEvent);
                });
            }
        });

        return $defenders->isDefeated($this->combatMoment);
    }

    protected function handleCombatFinished()
    {

    }

    protected function notifyEventHandlers(CombatEvent $combatEvent)
    {
        $this->eventHandlers->each(function (CombatEventHandler $eventHandler) use ($combatEvent) {
            $eventHandler->handleCombatEvent($combatEvent, $this->combatMoment);
        });
    }
}
