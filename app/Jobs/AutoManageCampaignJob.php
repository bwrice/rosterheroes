<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\AutoManageCampaign;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoJoinQuestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Squad
     */
    public $squad;

    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
    }

    /**
     * @param AutoManageCampaign $domainAction
     */
    public function handle(AutoManageCampaign $domainAction)
    {
        $domainAction->execute($this->squad);
    }
}
