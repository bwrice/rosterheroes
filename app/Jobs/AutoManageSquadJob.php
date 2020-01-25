<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\AutoManageSquadAction;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoManageSquadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Squad
     */
    public $squad;

    /**
     * AutoManageSquadJob constructor.
     * @param Squad $squad
     */
    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
    }

    /**
     * @param AutoManageSquadAction $domainAction
     * @throws \App\Exceptions\AutoManageSquadException
     */
    public function handle(AutoManageSquadAction $domainAction)
    {
        $domainAction->execute($this->squad);
    }
}
