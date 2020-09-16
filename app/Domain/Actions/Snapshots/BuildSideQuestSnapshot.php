<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestSnapshot;
use App\Facades\CurrentWeek;
use Illuminate\Support\Str;

class BuildSideQuestSnapshot extends BuildWeeklySnapshot
{

    public function handle(SideQuest $sideQuest)
    {
        /** @var SideQuestSnapshot $sideQuestSnapshot */
        $sideQuestSnapshot = $sideQuest->sideQuestSnapshots()->create([
            'uuid' => Str::uuid(),
            'week_id' => CurrentWeek::id(),
            'name' => $sideQuest->name,
            'difficulty' => $sideQuest->difficulty(),
            'experience_reward' => $sideQuest->getExperienceReward(),
            'favor_reward' => $sideQuest->getFavorReward(),
            'experience_per_moment' => $sideQuest->getExperiencePerMoment()
        ]);

        return $sideQuestSnapshot;
    }
}
