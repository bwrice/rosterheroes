<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\EmbodyNPCHero;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoManageNPCHeroesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Squad
     */
    public $npc;

    /**
     * AutoManageNPCHeroesJob constructor.
     * @param Squad $npc
     */
    public function __construct(Squad $npc)
    {
        $this->npc = $npc;
    }

    /**
     * @param EmbodyNPCHero $embodyNPCHero
     */
    public function handle(EmbodyNPCHero $embodyNPCHero)
    {
        $this->npc->heroes->each(function (Hero $hero) use ($embodyNPCHero) {
            $embodyNPCHero->execute($hero);
        });
    }
}
