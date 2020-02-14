<?php


namespace App\Factories\Models;


use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use Illuminate\Support\Str;

class SideQuestFactory
{
    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var SideQuest $sideQuest */
        $sideQuest = SideQuest::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Side Quest ' . rand(1, 99999),
            'quest_id' => factory(Quest::class)->create()->id
        ], $extra));
        return $sideQuest;
    }
}
