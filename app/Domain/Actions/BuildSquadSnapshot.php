<?php


namespace App\Domain\Actions;


use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use Illuminate\Support\Str;

class BuildSquadSnapshot
{
    public const EXCEPTION_CODE_SNAPSHOT_EXISTS = 1;
    public const EXCEPTION_CODE_WEEK_NOT_FINALIZING = 2;

    /**
     * @param Squad $squad
     * @param Week $week
     * @throws \Exception
     */
    public function execute(Squad $squad, Week $week)
    {
        if (now()->isBefore(WeekService::finalizingStartsAt($week->adventuring_locks_at))) {
            throw new \Exception("Too early to create Squad Snapshot", self::EXCEPTION_CODE_WEEK_NOT_FINALIZING);
        }

        $existingSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $squad->id)
            ->where('week_id', '=', $week->id)
            ->first();

        if ($existingSnapshot) {
            throw new \Exception("Snapshot for squad and week already exists", self::EXCEPTION_CODE_SNAPSHOT_EXISTS);
        }
    }
}
