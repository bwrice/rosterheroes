<?php

namespace App\Jobs;

use App\Domain\Actions\ClearWeeklyPlayerSpirit;
use App\Domain\Models\Hero;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearWeeklyPlayerSpiritJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Hero
     */
    public $hero;

    /**
     * Create a new job instance.
     *
     * @param Hero $hero
     * @return void
     */
    public function __construct(Hero $hero)
    {
        $this->hero = $hero;
    }

    /**
     * @param ClearWeeklyPlayerSpirit $domainAction
     * @throws \Exception
     */
    public function handle(ClearWeeklyPlayerSpirit $domainAction)
    {
        $domainAction->execute($this->hero);
    }
}
