<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Quest;
use App\Facades\CurrentWeek;
use App\Facades\NPC;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use Illuminate\Foundation\Bus\PendingDispatch;

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
        if ($adventuringLocksAt->isBefore(now()->addHours(1)->addMinutes(5))) {
            $message = "Cannot manage campaign of NPC, " . $this->npc->name;
            $message .= ", because adventuring locks in " . $adventuringLocksAt->diffInMinutes(now()) . " minutes";
            throw new \Exception($message, self::EXCEPTION_CODE_WEEK_LOCKS_TOO_SOON);
        }

        $questsToJoin = NPC::questsToJoin($this->npc);

        $chainedJobs = $questsToJoin->map(function ($questsToJoinArray) {
            /** @var Quest $quest */
            $quest = $questsToJoinArray['quest'];
            $chainedJobs = collect([new JoinQuestForNPCJob($this->npc, $quest)]);
            foreach ($questsToJoinArray['side_quests'] as $sideQuestToJoin) {
                $chainedJobs->push(new JoinSideQuestForNPCJob($this->npc, $sideQuestToJoin));
            }
            return MoveNPCToProvinceJob::withChain($chainedJobs->toArray())->dispatch($this->npc, $quest->province);
        });

        $now = now();
        $hoursBeforeLock = CurrentWeek::adventuringLocksAt()->diffInHours($now);
        $chainedJobs->each(function (PendingDispatch $chainedJob) use ($now, $hoursBeforeLock) {
            $delay = $now->clone()->addHours(rand(0, $hoursBeforeLock))->addMinutes(rand(0,60))->addSeconds(rand(0,60));
            $chainedJob->delay($delay);
        });
    }
}
