<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\OpenNPCChest;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OpenNPCChestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $npc;

    public function __construct(Squad $npc)
    {
        $this->npc = $npc;
    }

    /**
     * @param OpenNPCChest $openNPCChest
     * @throws \Exception
     */
    public function handle(OpenNPCChest $openNPCChest)
    {
        $openNPCChest->execute($this->npc);
    }
}
