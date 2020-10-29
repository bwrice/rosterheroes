<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Quest;
use App\Facades\CurrentWeek;
use App\Facades\NPC;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Bus;

class AutoManageNPCCampaign extends NPCAction
{
    public const EXCEPTION_CODE_WEEK_LOCKED = 7;
    public const EXCEPTION_CODE_WEEK_LOCKS_TOO_SOON = 8;

    public function handleExecute()
    {
        if (CurrentWeek::adventuringLocked()) {
            $message = "Cannot manage campaign of NPC: " . $this->npc->name . " when week is locked";
            throw new \Exception($message, self::EXCEPTION_CODE_WEEK_LOCKED);
        }

        $adventuringLocksAt = CurrentWeek::adventuringLocksAt();
        if ($adventuringLocksAt->isBefore(now()->addMinutes(25))) {
            $message = "Cannot manage campaign of NPC, " . $this->npc->name;
            $message .= ", because adventuring locks in " . $adventuringLocksAt->diffInMinutes(now()) . " minutes";
            throw new \Exception($message, self::EXCEPTION_CODE_WEEK_LOCKS_TOO_SOON);
        }

        $questsToJoin = NPC::questsToJoin($this->npc);

        $now = now();
        // TODO: delay based on time before week locks
        $hoursBeforeLock = CurrentWeek::adventuringLocksAt()->diffInHours($now);
        $count = 0;

        $questsToJoin->map(function ($questsToJoinArray)  use ($now, &$count) {
            /** @var Quest $quest */
            $quest = $questsToJoinArray['quest'];
            $chainedJobs = collect([new JoinQuestForNPCJob($this->npc, $quest)]);
            foreach ($questsToJoinArray['side_quests'] as $sideQuestToJoin) {
                $chainedJobs->push(new JoinSideQuestForNPCJob($this->npc, $sideQuestToJoin));
            }
            /** @var CarbonInterface $delay */
            $delay = $now->clone()->addSeconds(rand(40, 60) * $count);
            return MoveNPCToProvinceJob::withChain($chainedJobs->toArray())
                ->delay($delay)
                ->dispatch($this->npc, $quest->province);
        });
    }
}
