<?php


namespace App\Factories\Models;


use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\SideQuestResult;
use Illuminate\Support\Str;

class SideQuestResultFactory
{
    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): SideQuestResult
    {
        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create([
            'uuid' => Str::uuid()->toString(),
            'squad_id' => SquadFactory::new()->create()->id,
            'side_quest_id' => SideQuestFactory::new()->create()->id,
            'week_id' => factory(Week::class)->create()->id
        ]);

        return $sideQuestResult;
    }
}
