<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Quest;
use App\Jobs\SetupQuestForNextWeekJob;
use Illuminate\Support\Collection;

class SetupAllQuestsForNextWeek extends BatchedWeeklyAction
{
    protected string $name = 'Setup Quests';

    protected function jobs(): Collection
    {
        return Quest::all()->map(function (Quest $quest) {
            return new SetupQuestForNextWeekJob($quest);
        });
    }
}
