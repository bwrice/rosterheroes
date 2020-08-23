<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\JoinSideQuestForNPC;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JoinSideQuestForNPCJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Squad
     */
    public $npc;
    /**
     * @var Quest
     */
    public $sideQuest;

    public function __construct(Squad $npc, SideQuest $sideQuest)
    {
        $this->npc = $npc;
        $this->sideQuest = $sideQuest;
    }

    /**
     * @param JoinSideQuestForNPC $domainAction
     * @throws \Exception
     */
    public function handle(JoinSideQuestForNPC $domainAction)
    {
        $domainAction->execute($this->npc, $this->sideQuest);
    }
}
