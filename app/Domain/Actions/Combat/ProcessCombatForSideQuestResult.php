<?php


namespace App\Domain\Actions\Combat;

use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessCombatForSideQuestResult
{
    /**
     * @var int
     */
    protected $maxMoments = 500;
    /**
     * @var BuildCombatSquad
     */
    protected $buildCombatSquadAction;
    /**
     * @var BuildSideQuestGroup
     */
    protected $buildSideQuestGroup;
    /**
     * @var RunCombatTurn
     */
    protected $runCombatTurn;
    /**
     * @var ProcessSideQuestHeroAttack
     */
    protected $processSideQuestHeroAttack;
    /**
     * @var ProcessSideQuestMinionAttack
     */
    protected $processSideQuestMinionAttack;

    public function __construct(
        BuildCombatSquad $buildCombatSquadAction,
        BuildSideQuestGroup $buildSideQuestGroup,
        RunCombatTurn $runCombatTurn,
        ProcessSideQuestHeroAttack $processSideQuestHeroAttack,
        ProcessSideQuestMinionAttack $processSideQuestMinionAttack)
    {
        $this->buildCombatSquadAction = $buildCombatSquadAction;
        $this->buildSideQuestGroup = $buildSideQuestGroup;
        $this->runCombatTurn = $runCombatTurn;
        $this->processSideQuestHeroAttack = $processSideQuestHeroAttack;
        $this->processSideQuestMinionAttack = $processSideQuestMinionAttack;
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @return SideQuestResult
     * @throws \Throwable
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->combat_processed_at) {
            throw new \Exception("Combat already processed for side-quest-result: " . $sideQuestResult->id);
        }

        $sideQuestResult->combat_processed_at = Date::now();
        $sideQuestResult->save();

        /** @var CombatPositionCollection $combatPositions */
        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();
        $damageTypes = DamageType::all();

        $combatSquad = $this->buildCombatSquadAction->execute($sideQuestResult->campaignStop->campaign->squad, $combatPositions, $targetPriorities, $damageTypes);
        $sideQuestGroup = $this->buildSideQuestGroup->execute($sideQuestResult->sideQuest, $combatPositions, $targetPriorities, $damageTypes);

        try {
            return DB::transaction(function () use ($sideQuestResult, $combatSquad, $sideQuestGroup, $combatPositions) {

                $battlegroundSetEvent = $this->getSideQuestEvent(SideQuestEvent::TYPE_BATTLEGROUND_SET, 0, $combatSquad, $sideQuestGroup);
                $sideQuestResult->sideQuestEvents()->save($battlegroundSetEvent);

                 list($moment, $eventType) = $this->loopCombat($sideQuestResult, $combatSquad, $sideQuestGroup, $combatPositions);
                 $finalEventType = $this->getSideQuestEvent($eventType, $moment, $combatSquad, $sideQuestGroup);
                 $sideQuestResult->sideQuestEvents()->save($finalEventType);

                return $sideQuestResult->fresh();
            });
        } catch (\Throwable $exception) {

            $sideQuestResult->combat_processed_at = null;
            $sideQuestResult->save();
            throw $exception;
        }
    }

    /**
     * @param int $maxMoments
     * @return ProcessCombatForSideQuestResult
     */
    public function setMaxMoments(int $maxMoments): ProcessCombatForSideQuestResult
    {
        $this->maxMoments = $maxMoments;
        return $this;
    }

    /**
     * @param CombatSquad $combatSquad
     * @param HeroCombatAttack $heroCombatAttack
     * @return CombatHero
     */
    protected function getCombatHeroByHeroCombatAttack(CombatSquad $combatSquad, HeroCombatAttack $heroCombatAttack)
    {
        return $combatSquad->getCombatHeroes()->first(function (CombatHero $combatHero) use ($heroCombatAttack) {
            return $combatHero->getHeroUuid() === $heroCombatAttack->getHeroUuid();
        });
    }

    /**
     * @param SideQuestGroup $sideQuestGroup
     * @param MinionCombatAttack $minionCombatAttack
     * @return CombatMinion
     */
    protected function getCombatMinionByMinionCombatAttack(SideQuestGroup $sideQuestGroup, MinionCombatAttack $minionCombatAttack)
    {
        return $sideQuestGroup->getCombatMinions()->first(function (CombatMinion $combatMinion) use ($minionCombatAttack) {
            return $combatMinion->getCombatantUuid() === $minionCombatAttack->getCombatantUuid();
        });
    }

    protected function getSideQuestEvent(string $eventType, int $moment, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        return new SideQuestEvent([
            'uuid' => (string) Str::uuid(),
            'event_type' => $eventType,
            'moment' => $moment,
            'data' => [
                'combatSquad' => $combatSquad->toArray(),
                'sideQuestGroup' => $sideQuestGroup->toArray()
            ]
        ]);
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param CombatSquad $combatSquad
     * @param SideQuestGroup $sideQuestGroup
     * @param CombatPositionCollection $combatPositions
     * @return array
     */
    protected function loopCombat(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup, CombatPositionCollection $combatPositions)
    {
        $moment = 1;
        while (true) {

            if ($combatSquad->isDefeated()) {
                return [
                    $moment,
                    SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT
                ];
            } else {

                /*
                 * CombatSquad Turn
                 */
                $this->runCombatTurn->execute($combatSquad, $sideQuestGroup, $moment, $combatPositions,
                    function ($damageReceived, HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, $block) use ($combatSquad, $sideQuestResult, $moment) {
                        $combatHero = $this->getCombatHeroByHeroCombatAttack($combatSquad, $heroCombatAttack);
                        $sideQuestEvent = $this->processSideQuestHeroAttack->execute($moment, $damageReceived, $combatHero, $heroCombatAttack, $combatMinion, $block);
                        $sideQuestResult->sideQuestEvents()->save($sideQuestEvent);
                    });

                if ($sideQuestGroup->isDefeated()) {
                    return [
                        $moment,
                        SideQuestEvent::TYPE_SIDE_QUEST_VICTORY
                    ];
                } else {
                    /*
                     * SideQuestGroup Turn
                     */
                    $this->runCombatTurn->execute($sideQuestGroup, $combatSquad, $moment, $combatPositions,
                        function ($damageReceived, MinionCombatAttack $minionCombatAttack, CombatHero $combatHero, $block) use ($sideQuestGroup, $sideQuestResult, $moment) {
                            $combatMinion = $this->getCombatMinionByMinionCombatAttack($sideQuestGroup, $minionCombatAttack);
                            $sideQuestEvent = $this->processSideQuestMinionAttack->execute($moment, $damageReceived, $combatMinion, $minionCombatAttack, $combatHero, $block);
                            $sideQuestResult->sideQuestEvents()->save($sideQuestEvent);
                        });
                }
            }

            if ($moment >= $this->maxMoments) {
                return [
                    $moment,
                    SideQuestEvent::TYPE_SIDE_QUEST_DRAW
                ];
            }

            $moment++;
        }
        // This return needed for phpStorm analyzer
        return [];
    }
}
