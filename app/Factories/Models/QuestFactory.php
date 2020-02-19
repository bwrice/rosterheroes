<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\TravelType;
use Illuminate\Support\Str;

class QuestFactory
{
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Quest
     */
    public function create(array $extra = []): Quest
    {
        /** @var Quest $quest */
        $quest = Quest::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Quest ' . rand(1, 999999),
            'level' => 200,
            'percent' => 100,
            'province_id' => Province::query()->inRandomOrder()->first()->id,
            'travel_type_id' => TravelType::query()->inRandomOrder()->first()->id,
        ], $extra));
        return $quest;
    }
}
