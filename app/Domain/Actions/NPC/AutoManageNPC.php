<?php


namespace App\Domain\Actions\NPC;



use App\Domain\Models\Chest;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use App\Jobs\OpenChestJob;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

/**
 * Class AutoManageNPC
 * @package App\Domain\Actions\NPC
 *
 * @method execute(Squad $squad, float $triggerChance, int $maxDelayMinutes, $actions = self::DEFAULT_ACTIONS)
 */
class AutoManageNPC extends NPCAction
{
    public const ACTION_OPEN_CHESTS = 'open-chests';
    public const ACTION_JOIN_QUESTS = 'join-quests';

    public const DEFAULT_ACTIONS = [
        self::ACTION_OPEN_CHESTS,
        self::ACTION_JOIN_QUESTS
    ];

    protected FindChestsToOpen $findChestsToOpen;
    protected FindQuestsToJoin $findQuestsToJoin;

    public function __construct(
        FindChestsToOpen $findChestsToOpen,
        FindQuestsToJoin $findQuestsToJoin)
    {
        $this->findChestsToOpen = $findChestsToOpen;
        $this->findQuestsToJoin = $findQuestsToJoin;
    }

    /**
     * @param float $triggerChance
     * @param int $maxDelayMinutes
     * @param $actions
     * @throws \Exception
     */
    public function handleExecute(float $triggerChance, int $maxDelayMinutes, $actions = self::DEFAULT_ACTIONS)
    {
        /** @var CarbonInterface $initialDelay */
        $initialDelay = now()->addMinutes(rand(1, $maxDelayMinutes));
        $jobs = collect();
        $secondsDelay = 0;

        $actions = collect($actions);
        $actions->each(function ($action) use (&$jobs, &$secondsDelay, $initialDelay, $triggerChance) {

            if (rand(1, 100) <= $triggerChance) {

                $jobsToAdd = collect();
                switch ($action) {
                    case self::ACTION_OPEN_CHESTS:
                        $jobsToAdd = $this->getOpenChestJobs();
                        break;
                    case self::ACTION_JOIN_QUESTS:
                        $jobsToAdd = $this->getJoinQuestJobs();
                        break;
                }

                $jobsToAdd->each(function ($job) use(&$secondsDelay, $initialDelay) {
                    /** @var Queueable $job */
                    $secondsDelay += rand(4, 60);
                    $job->delay($initialDelay->addSeconds($secondsDelay));
                });
                $jobs = $jobs->merge($jobsToAdd);
            }
        });

        if ($jobs->isNotEmpty()) {
            Bus::chain($jobs->toArray())->dispatch();
        }
    }

    protected function getOpenChestJobs()
    {
        $chests = $this->findChestsToOpen->execute($this->npc);
        return $chests->map(function (Chest $chest) {
            return new OpenChestJob($chest);
        });
    }

    protected function getJoinQuestJobs()
    {
        $questArrays = $this->findQuestsToJoin->execute($this->npc);
        $jobs = collect();
        $questArrays->each(function ($questArray) use ($jobs) {

            /** @var Quest $quest */
            $quest = $questArray['quest'];
            $jobs->push(new MoveNPCToProvinceJob($this->npc, $quest->province));
            $jobs->push(new JoinQuestForNPCJob($this->npc, $quest));

            /** @var Collection $sideQuests */
            $sideQuests = $questArray['side_quests'];
            $sideQuests->each(function (SideQuest $sideQuest) use ($jobs) {
                $jobs->push(new JoinSideQuestForNPCJob($this->npc, $sideQuest));
            });
        });
        return $jobs;
    }
}
