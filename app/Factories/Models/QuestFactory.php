<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\TravelType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QuestFactory
{
    protected $provinceID;

    /** @var Collection|null */
    protected $sideQuestFactories;

    /** @var string|null */
    protected $travelTypeName;

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
        $provinceID = $this->getProvinceID();

        /** @var Quest $quest */
        $quest = Quest::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Quest ' . rand(1, 999999),
            'level' => 200,
            'percent' => 100,
            'province_id' => $provinceID,
            'initial_province_id' => $provinceID,
            'travel_type_id' => $this->getTravelTypeID(),
        ], $extra));

        if ($this->sideQuestFactories) {
            $this->sideQuestFactories->each(function (SideQuestFactory $sideQuestFactory) use ($quest) {
                $sideQuestFactory->forQuestID($quest->id)->create();
            });
        }

        return $quest;
    }

    public function withProvinceID(int $provinceID)
    {
        $clone = clone $this;
        $clone->provinceID = $provinceID;
        return $clone;
    }

    protected function getProvinceID()
    {
        return $this->provinceID ?: Province::query()->inRandomOrder()->first()->id;
    }

    public function withSideQuests(Collection $sideQuestFactories)
    {
        $clone = clone $this;
        $clone->sideQuestFactories = $sideQuestFactories;
        return $clone;
    }

    public function withTravelType(string $travelTypeName)
    {
        $clone = clone $this;
        $clone->travelTypeName = $travelTypeName;
        return $clone;
    }

    protected function getTravelTypeID()
    {
        if ($this->travelTypeName) {
            return TravelType::forName($this->travelTypeName)->id;
        }
        return TravelType::query()->inRandomOrder()->first()->id;
    }
}
