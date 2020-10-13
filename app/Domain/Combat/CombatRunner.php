<?php


namespace App\Domain\Combat;


use App\Domain\Actions\Combat\CombatEventHandler;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Combat\CombatGroups\CombatGroup;
use App\Domain\Combat\Events\CombatEvent;
use Illuminate\Support\Collection;

class CombatRunner
{
    public const SIDE_A = 'SIDE-A';
    public const SIDE_B = 'SIDE-B';

    protected RunCombatTurn $runCombatTurn;
    protected Collection $turnAHandlers;
    protected Collection $turnBHandlers;

    public function __construct(RunCombatTurn $runCombatTurn)
    {
        $this->runCombatTurn = $runCombatTurn;
        $this->turnAHandlers = collect();
        $this->turnBHandlers = collect();
    }

    /**
     * @param CombatGroup $sideA
     * @param CombatGroup $sideB
     * @param int $maxMoments
     * @return array
     */
    public function execute(CombatGroup $sideA, CombatGroup $sideB, int $maxMoments = 500)
    {
        $moment = 1;
        $victoriousSide = null;
        while ($moment <= $maxMoments && ! $victoriousSide) {

            /*
             * Side A Attacks
             */
            $events = $this->runCombatTurn->execute($sideA, $sideB, $moment);
            $events->each(function (CombatEvent $combatEvent) {
                $stream = $combatEvent->eventStream();
                $this->turnAHandlers->each(function (CombatEventHandler $turnAHandler) use ($stream, $combatEvent) {
                    if (in_array($stream, $turnAHandler->streams())) {
                        $turnAHandler->handle($combatEvent);
                    }
                });
            });

            if ($sideB->isDefeated($moment)) {
                $victoriousSide = self::SIDE_A;
            } else {
                /*
                 * Side B Attacks
                 */
                $events = $this->runCombatTurn->execute($sideB, $sideA, $moment);
                $events->each(function (CombatEvent $combatEvent) {
                    $stream = $combatEvent->eventStream();
                    $this->turnBHandlers->each(function (CombatEventHandler $turnAHandler) use ($stream, $combatEvent) {
                        if (in_array($stream, $turnAHandler->streams())) {
                            $turnAHandler->handle($combatEvent);
                        }
                    });
                });
            }

            if ($sideA->isDefeated($moment)) {
                $victoriousSide = self::SIDE_B;
            }
            $moment++;
        }

        return [
            'victorious_side' => $victoriousSide,
            'moment' => $moment
        ];
    }

    public function registerTurnAHandler(CombatEventHandler $combatEventHandler)
    {
        $this->turnAHandlers->push($combatEventHandler);
    }

    public function registerTurnBHandler(CombatEventHandler $combatEventHandler)
    {
        $this->turnBHandlers->push($combatEventHandler);
    }
}
