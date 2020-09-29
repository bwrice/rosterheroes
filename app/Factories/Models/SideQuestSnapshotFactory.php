<?php


namespace App\Factories\Models;


use App\Domain\Models\SideQuestSnapshot;
use App\Domain\Models\Week;
use Illuminate\Support\Str;

class SideQuestSnapshotFactory
{
    
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return SideQuestSnapshot
     */
    public function create(array $extra = [])
    {
        /** @var SideQuestSnapshot $sideQuestSnapshot */
        $sideQuestSnapshot = SideQuestSnapshot::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'week_id' => factory(Week::class)->create()->id,
            'side_quest_id' => SideQuestFactory::new()->create()->id,
            'name' => 'Test SideQuest Snapshot ' . rand(1, 99999),
            'difficulty' => rand(10, 250),
            'experience_reward' => rand(250, 99999),
            'favor_reward' => rand(2, 25),
            'experience_per_moment' => rand(1, 3)
        ], $extra));

        return $sideQuestSnapshot;
    }
}
