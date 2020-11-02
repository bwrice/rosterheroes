<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\AutoManageHeroAction;
use App\Domain\Models\Hero;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoManageHeroJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Hero
     */
    public $hero;

    public function __construct(Hero $hero)
    {
        $this->hero = $hero;
    }

    /**
     * @param AutoManageHeroAction $domainAction
     */
    public function handle(AutoManageHeroAction $domainAction)
    {
        $domainAction->execute($this->hero);
    }
}
