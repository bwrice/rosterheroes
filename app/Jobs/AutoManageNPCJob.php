<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\AutoManageNPC;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoManageNPCJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $npc;
    public float $triggerChance;
    public ?array $actions;

    public function __construct(Squad $npc, float $triggerChance, ?array $actions = null)
    {
        $this->npc = $npc;
        $this->triggerChance = $triggerChance;
        $this->actions = $actions;
    }

    /**
     * @param AutoManageNPC $autoManageNPC
     * @throws \Exception
     */
    public function handle(AutoManageNPC $autoManageNPC)
    {
        $autoManageNPC->execute($this->npc, $this->triggerChance, $this->actions);
    }
}
