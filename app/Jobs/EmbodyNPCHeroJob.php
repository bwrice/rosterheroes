<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\EmbodyNPCHero;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmbodyNPCHeroJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Hero $hero;
    public PlayerSpirit $playerSpirit;

    public function __construct(Hero $hero, PlayerSpirit $playerSpirit)
    {
        $this->hero = $hero;
        $this->playerSpirit = $playerSpirit;
    }

    /**
     * @param EmbodyNPCHero $embodyNPCHero
     * @throws \Exception
     */
    public function handle(EmbodyNPCHero $embodyNPCHero)
    {
        $embodyNPCHero->execute($this->hero, $this->playerSpirit);
    }
}
