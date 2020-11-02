<?php


namespace App\Domain\Actions\Combat;

use App\Domain\Actions\ConvertSideQuestSnapshotIntoSideQuestCombatGroup;
use App\Domain\Actions\ConvertSquadSnapshotIntoCombatSquad;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Domain\Combat\CombatRunner;
use App\Domain\Combat\Events\Handlers\HeroBlocksMinionHandler;
use App\Domain\Combat\Events\Handlers\HeroDamagesMinionHandler;
use App\Domain\Combat\Events\Handlers\HeroKillsMinionHandler;
use App\Domain\Combat\Events\Handlers\MinionBlocksHeroHandler;
use App\Domain\Combat\Events\Handlers\MinionDamagesHeroHandler;
use App\Domain\Combat\Events\Handlers\MinionKillsHeroHandler;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class ProcessCombatForSideQuestResult
{
    public const EXCEPTION_CODE_SIDE_QUEST_ALREADY_PROCESSED = 1;
    public const EXCEPTION_CODE_NO_SQUAD_SNAPSHOT = 2;
    public const EXCEPTION_CODE_NO_SIDE_QUEST_SNAPSHOT = 3;

    protected ConvertSquadSnapshotIntoCombatSquad $convertSquadSnapshotIntoCombatSquad;
    protected ConvertSideQuestSnapshotIntoSideQuestCombatGroup $convertSideQuestSnapshotIntoSideQuestCombatGroup;
    protected CombatRunner $combatRunner;

    public function __construct(
        ConvertSquadSnapshotIntoCombatSquad $convertSquadSnapshotIntoCombatSquad,
        ConvertSideQuestSnapshotIntoSideQuestCombatGroup $convertSideQuestSnapshotIntoSideQuestCombatGroup,
        CombatRunner $combatRunner)
    {
        $this->convertSquadSnapshotIntoCombatSquad = $convertSquadSnapshotIntoCombatSquad;
        $this->convertSideQuestSnapshotIntoSideQuestCombatGroup = $convertSideQuestSnapshotIntoSideQuestCombatGroup;
        $this->combatRunner = $combatRunner;
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $maxMoments
     * @return SideQuestResult
     * @throws \Throwable
     */
    public function execute(SideQuestResult $sideQuestResult, int $maxMoments = 5000)
    {
        if ($sideQuestResult->combat_processed_at) {
            throw new \Exception("Combat already processed for side-quest-result: " . $sideQuestResult->id, self::EXCEPTION_CODE_SIDE_QUEST_ALREADY_PROCESSED);
        }

        $squadSnapshot = $sideQuestResult->squadSnapshot;
        if (! $squadSnapshot) {
            throw new \Exception("No squad snapshot for side-quest-result: " . $sideQuestResult->id, self::EXCEPTION_CODE_NO_SQUAD_SNAPSHOT);
        }

        $sideQuestSnapshot = $sideQuestResult->sideQuestSnapshot;
        if (! $sideQuestSnapshot) {
            throw new \Exception("No side-quest snapshot for side-quest-result: " . $sideQuestResult->id, self::EXCEPTION_CODE_NO_SIDE_QUEST_SNAPSHOT);
        }

        // Immediately set combat-processed so it can't be processed concurrently elsewhere
        $sideQuestResult->combat_processed_at = Date::now();
        $sideQuestResult->save();

        $combatSquad = $this->convertSquadSnapshotIntoCombatSquad->execute($squadSnapshot);
        $sideQuestGroup = $this->convertSideQuestSnapshotIntoSideQuestCombatGroup->execute($sideQuestSnapshot);

        /*
         * A DB transaction can be potentially too long for InnoDB Log Buffer.
         * So we'll handle rolling back everything manually with a try-catch
         */
        try {

            $this->createBattlegroundSetEvent($sideQuestResult, $combatSquad, $sideQuestGroup);
            $combatResult = $this->runCombat($sideQuestResult, $combatSquad, $sideQuestGroup, $maxMoments);
            $this->createEndEvent($sideQuestResult, $combatSquad, $sideQuestGroup, $combatResult);

            return $sideQuestResult->fresh();
        } catch (\Throwable $exception) {

            $sideQuestResult->sideQuestEvents()->delete();
            $sideQuestResult->combat_processed_at = null;
            $sideQuestResult->save();
            throw $exception;
        }
    }

    protected function createBattlegroundSetEvent(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestCombatGroup $sideQuestGroup)
    {
        $sideQuestResult->sideQuestEvents()->create([
            'uuid' => (string) Str::uuid(),
            'moment' => 0,
            'event_type' => SideQuestEvent::TYPE_BATTLEGROUND_SET,
            'data' => [
                'combat_squad' => $combatSquad->toArray(),
                'side_quest_group' => $sideQuestGroup->toArray()
            ]
        ]);
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param CombatSquad $combatSquad
     * @param SideQuestCombatGroup $sideQuestGroup
     * @param int $maxMoments
     * @return array
     */
    protected function runCombat(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestCombatGroup $sideQuestGroup, int $maxMoments)
    {
        $this->combatRunner->registerTurnAHandler(new HeroDamagesMinionHandler($sideQuestResult, $combatSquad, $sideQuestGroup))
            ->registerTurnAHandler(new HeroKillsMinionHandler($sideQuestResult, $combatSquad, $sideQuestGroup))
            ->registerTurnAHandler(new MinionBlocksHeroHandler($sideQuestResult, $combatSquad, $sideQuestGroup))
            ->registerTurnBHandler(new MinionDamagesHeroHandler($sideQuestResult, $combatSquad, $sideQuestGroup))
            ->registerTurnBHandler(new MinionKillsHeroHandler($sideQuestResult, $combatSquad, $sideQuestGroup))
            ->registerTurnBHandler(new HeroBlocksMinionHandler($sideQuestResult, $combatSquad, $sideQuestGroup));

        return $this->combatRunner->execute($combatSquad, $sideQuestGroup, $maxMoments);
    }

    protected function createEndEvent(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestCombatGroup $sideQuestGroup, array $combatResult)
    {
        if (! $combatResult['victorious_side']) {
            $eventType = SideQuestEvent::TYPE_SIDE_QUEST_DRAW;
        } else {
            $eventType = $combatResult['victorious_side'] == CombatRunner::SIDE_A ?
                SideQuestEvent::TYPE_SIDE_QUEST_VICTORY : SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT;
        }

        $sideQuestResult->sideQuestEvents()->create([
            'uuid' => (string) Str::uuid(),
            'event_type' => $eventType,
            'moment' => $combatResult['moment'],
            'data' => [
                'combat_squad' => $combatSquad->toArray(),
                'side_quest_group' => $sideQuestGroup->toArray()
            ]
        ]);
    }
}
