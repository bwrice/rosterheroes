<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\TargetPriority;
use App\Facades\CurrentWeek;
use App\SideQuestResult;
use Illuminate\Support\Str;

class ProcessSideQuestResult
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
    /**
     * @var CreateBattlefieldSetForSideQuestEvent
     */
    protected $createBattlefieldSetForSideQuestEvent;

    public function __construct(
        BuildCombatSquad $buildCombatSquadAction,
        BuildSideQuestGroup $buildSideQuestGroup,
        RunCombatTurn $runCombatTurn,
        CreateBattlefieldSetForSideQuestEvent $createBattlefieldSetForSideQuestEvent,
        ProcessSideQuestHeroAttack $processSideQuestHeroAttack,
        ProcessSideQuestMinionAttack $processSideQuestMinionAttack)
    {
        $this->buildCombatSquadAction = $buildCombatSquadAction;
        $this->buildSideQuestGroup = $buildSideQuestGroup;
        $this->runCombatTurn = $runCombatTurn;
        $this->createBattlefieldSetForSideQuestEvent = $createBattlefieldSetForSideQuestEvent;
        $this->processSideQuestHeroAttack = $processSideQuestHeroAttack;
        $this->processSideQuestMinionAttack = $processSideQuestMinionAttack;
    }

    /**
     * @param CampaignStop $campaignStop
     * @param SideQuest $sideQuest
     * @return SideQuestResult
     */
    public function execute(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        /** @var CombatPositionCollection $combatPositions */
        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();
        $damageTypes = DamageType::all();
        $combatSquad = $this->buildCombatSquadAction->execute($campaignStop->campaign->squad, $combatPositions, $targetPriorities, $damageTypes);
        $sideQuestGroup = $this->buildSideQuestGroup->execute($sideQuest, $combatPositions, $targetPriorities, $damageTypes);

        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create([
            'uuid' => Str::uuid()->toString(),
            'side_quest_id' => $sideQuest->id,
            'campaign_stop_id' => $campaignStop->id,
        ]);

        $this->createBattlefieldSetEvent($sideQuestResult, $combatSquad, $sideQuestGroup);

        $moment = 1;
        $continueBattle = true;

        while($continueBattle) {

            if ($combatSquad->isDefeated()) {
                $continueBattle = false;
                $this->createSideQuestDefeatEvent($sideQuestResult, $moment, $combatSquad, $sideQuestGroup);
            } else {

                /*
                 * CombatSquad Turn
                 */
                $this->runCombatTurn->execute($combatSquad, $sideQuestGroup, $moment, $combatPositions,
                    function($damageReceived, HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, $block) use ($combatSquad, $sideQuestResult, $moment) {
                        $combatHero = $this->getCombatHeroByHeroCombatAttack($combatSquad, $heroCombatAttack);
                        $this->processSideQuestHeroAttack->execute($sideQuestResult, $moment, $damageReceived, $combatHero, $heroCombatAttack, $combatMinion, $block);
                    });

                if ($sideQuestGroup->isDefeated()) {
                    $continueBattle = false;
                    $this->createSideQuestVictory($sideQuestResult, $moment, $combatSquad, $sideQuestGroup);
                } else {
                    /*
                     * SideQuestGroup Turn
                     */
                    $this->runCombatTurn->execute($sideQuestGroup, $combatSquad, $moment, $combatPositions,
                        function($damageReceived, MinionCombatAttack $minionCombatAttack, CombatHero $combatHero, $block) use ($sideQuestGroup, $sideQuestResult, $moment) {
                            $combatMinion = $this->getCombatMinionByMinionCombatAttack($sideQuestGroup, $minionCombatAttack);
                            $this->processSideQuestMinionAttack->execute($sideQuestResult, $moment, $damageReceived, $combatMinion, $minionCombatAttack, $combatHero, $block);
                        });
                }
            }

            if ($continueBattle && $moment >= $this->maxMoments) {
                $continueBattle = false;
                $this->createSideQuestDraw($sideQuestResult, $moment, $combatSquad, $sideQuestGroup);
            }

            $moment++;
        }

        return $sideQuestResult;
    }

    /**
     * @param int $maxMoments
     * @return ProcessSideQuestResult
     */
    public function setMaxMoments(int $maxMoments): ProcessSideQuestResult
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
            return $combatMinion->getMinionUuid() === $minionCombatAttack->getMinionUuid();
        });
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param CombatSquad $combatSquad
     * @param SideQuestGroup $sideQuestGroup
     */
    protected function createBattlefieldSetEvent(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $aggregate->createBattlefieldSetEvent($sideQuestResult->id, [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ])->persist();
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param CombatSquad $combatSquad
     * @param SideQuestGroup $sideQuestGroup
     */
    protected function createSideQuestDefeatEvent(SideQuestResult $sideQuestResult, int $moment, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $aggregate->recordSideQuestDefeat($sideQuestResult->id, $moment, [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ])->persist();
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param CombatSquad $combatSquad
     * @param SideQuestGroup $sideQuestGroup
     */
    protected function createSideQuestVictory(SideQuestResult $sideQuestResult, int $moment, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $aggregate->recordSideQuestVictory($sideQuestResult->id, $moment, [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ])->persist();
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param CombatSquad $combatSquad
     * @param SideQuestGroup $sideQuestGroup
     */
    protected function createSideQuestDraw(SideQuestResult $sideQuestResult, int $moment, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $aggregate->recordSideQuestDraw($sideQuestResult->id, $moment, [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ])->persist();
    }
}
