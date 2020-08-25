<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\JoinQuestForNPC;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JoinQuestForNPCJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Squad
     */
    public $npc;
    /**
     * @var Quest
     */
    public $quest;

    public function __construct(Squad $npc, Quest $quest)
    {
        $this->npc = $npc;
        $this->quest = $quest;
    }

    /**
     * @param JoinQuestForNPC $domainAction
     * @throws \Exception
     */
    public function handle(JoinQuestForNPC $domainAction)
    {
        $domainAction->execute($this->npc, $this->quest);
    }
}
