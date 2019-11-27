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

    public function execute(CombatGroup $sideA, CombatGroup $sideB, $maxMoments = 5000)
    {
        while($this->combatMoment->getCount() <= $maxMoments) {

            $combatActions = $sideA->getCombatActions($this->combatMoment->getCount());
            $combatActions->each(function (CombatAttack $combatAttack) use ($sideB) {
                $combatEvents = $sideB->receiveAttack($combatAttack);
                $combatEvents->each(function (CombatEvent $combatEvent) {
                    $this->notifyEventOccurred($combatEvent);
                });
            });

            $this->combatMoment->tick();;
        }
    }

    protected function notifyEventOccurred(CombatEvent $combatEvent)
    {
        $this->eventHandlers->each(function (CombatEventHandler $eventHandler) use ($combatEvent) {
            $eventHandler->handleCombatEvent($combatEvent, $this->combatMoment);
        });
    }
}
