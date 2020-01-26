<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\AutoJoinQuestAction;
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
     * @param AutoJoinQuestAction $domainAction
     */
    public function handle(AutoJoinQuestAction $domainAction)
    {
        foreach (range (1, $this->squad->getQuestsPerWeek()) as $count) {
            if ($count > 1) {
                $this->squad = $this->squad->fresh();
            }
            $domainAction->execute($this->squad);
        }
    }
}
