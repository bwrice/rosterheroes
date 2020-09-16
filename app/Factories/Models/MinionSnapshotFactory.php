<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Week;
use Illuminate\Support\Str;

class MinionSnapshotFactory
{
    /** @var int|null */
    protected $weekID;
    /** @var int|null */
    protected $minionID;


    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var MinionSnapshot $minionSnapshot */
        $minionSnapshot = MinionSnapshot::query()->create(array_merge([
            'uuid' => Str::uuid(),
            'week_id' => $this->getWeekID(),
            'minion_id' => $this->getMinionID(),
            'level' => rand(1, 100),
            'combat_position_id' => CombatPosition::query()->inRandomOrder()->first()->id,
            'enemy_type_id' => EnemyType::query()->inRandomOrder()->first()->id,
            'starting_health' => rand(400, 10000),
            'protection' => rand(0, 2000),
            'block_chance' => round(rand(0, 3000)/100, 2),
            'fantasy_power' => rand(5, 40),
            'experience_reward' => rand(50, 2500),
            'favor_reward' => rand(1, 25)
        ], $extra));
        return $minionSnapshot;
    }

    protected function getWeekID()
    {
        if ($this->weekID) {
            return $this->weekID;
        }
        return factory(Week::class)->create()->id;
    }

    protected function getMinionID()
    {
        if ($this->minionID) {
            return $this->minionID;
        }
        return MinionFactory::new()->create()->id;
    }

    public function withWeekID(int $weekID)
    {
        $clone = clone $this;
        $clone->weekID = $weekID;
        return $clone;
    }

    public function withMinionID(int $minionID)
    {
        $clone = clone $this;
        $clone->minionID = $minionID;
        return $clone;
    }
}
