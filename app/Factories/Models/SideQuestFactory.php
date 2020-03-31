<?php


namespace App\Factories\Models;


use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SideQuestFactory
{
    protected $questID;

    /**
     * @var  Collection|null
     */
    protected $minionFactories = null;

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
            'quest_id' => $this->getQuestID()
        ], $extra));

        if ($this->minionFactories) {
            $this->minionFactories->each(function (MinionFactory $factory, $key) use ($sideQuest) {
                $minion = $factory->create();
                $count = $factory->getCountForSideQuest() ?: rand(1, 3);
                $sideQuest->minions()->save($minion, [
                    'count' => $count
                ]);
            });
        }
        return $sideQuest->fresh();
    }

    public function withMinions(Collection $minionFactories = null)
    {
        $clone = clone $this;
        $clone->minionFactories = $minionFactories ?: $this->getDefaultMinionFactories();
        return $clone;
    }

    /**
     * @return Collection
     */
    protected function getDefaultMinionFactories()
    {
        $amount = rand(1, 5);
        $minionFactories = collect();
        foreach (range(1, $amount) as $factoryCount) {
            $minionFactories->push(MinionFactory::new());
        }
        return $minionFactories;
    }

    public function forQuestID(int $questID)
    {
        $clone = clone $this;
        $clone->questID = $questID;
        return $clone;
    }

    protected function getQuestID()
    {
        return $this->questID ?: QuestFactory::new()->create()->id;
    }
}
