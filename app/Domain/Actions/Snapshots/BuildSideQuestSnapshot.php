<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestSnapshot;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuildSideQuestSnapshot extends BuildWeeklySnapshot
{
    public const EXCEPTION_MINION_SNAPSHOT_NOT_FOUND = 6;

    /**
     * @param SideQuest $sideQuest
     * @return SideQuestSnapshot
     */
    public function handle(SideQuest $sideQuest)
    {
        return DB::transaction(function () use ($sideQuest) {

            $currentWeekID = CurrentWeek::id();

            /** @var SideQuestSnapshot $sideQuestSnapshot */
            $sideQuestSnapshot = $sideQuest->sideQuestSnapshots()->create([
                'uuid' => Str::uuid(),
                'week_id' => $currentWeekID,
                'name' => $sideQuest->name,
                'difficulty' => $sideQuest->difficulty(),
                'experience_reward' => $sideQuest->getExperienceReward(),
                'favor_reward' => $sideQuest->getFavorReward(),
                'experience_per_moment' => $sideQuest->getExperiencePerMoment()
            ]);

            $sideQuest->minions->each(function (Minion $minion) use ($sideQuestSnapshot, $currentWeekID) {
                $minionSnapshot = MinionSnapshot::query()
                    ->where('week_id', '=', $currentWeekID)
                    ->where('minion_id', '=', $minion->id)
                    ->first();
                if (is_null($minionSnapshot)) {
                    throw new \Exception("Minion snapshot for minion: " . $minion->name . " not found", self::EXCEPTION_MINION_SNAPSHOT_NOT_FOUND);
                }

                $sideQuestSnapshot->minionSnapshots()->save($minionSnapshot, [
                    'count' => $minion->pivot->count
                ]);
            });

            return $sideQuestSnapshot;
        });
    }
}
