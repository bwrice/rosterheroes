<?php


namespace App\Domain\Actions;


use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;

class BuildSquadSnapshot
{
    public const EXCEPTION_CODE_SNAPSHOT_EXISTS = 1;

    public function execute(Squad $squad, Week $week)
    {
        $existingSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $squad->id)
            ->where('week_id', '=', $week->id)
            ->first();

        if ($existingSnapshot) {
            throw new \Exception("Snapshot for squad and week already exists", self::EXCEPTION_CODE_SNAPSHOT_EXISTS);
        }
    }
}
