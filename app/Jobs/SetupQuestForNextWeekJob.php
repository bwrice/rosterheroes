<?php

namespace App\Jobs;

use App\Domain\Actions\SetupQuestForNextWeek;
use App\Domain\Models\Quest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetupQuestForNextWeekJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Quest
     */
    public $quest;

    /**
     * Create a new job instance.
     *
     * @param Quest $quest
     * @return void
     */
    public function __construct(Quest $quest)
    {
        $this->quest = $quest;
    }

    /**
     * @param SetupQuestForNextWeek $setupQuestForNextWeek
     * @throws \Exception
     */
    public function handle(SetupQuestForNextWeek $setupQuestForNextWeek)
    {
        $setupQuestForNextWeek->execute($this->quest);
    }
}
