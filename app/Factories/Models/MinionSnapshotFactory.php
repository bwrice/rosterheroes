<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Week;
use Illuminate\Support\Str;

class MinionSnapshotFactory
{
    protected ?Minion $minion = null;
    protected ?int $weekID = null;
    protected ?int $minionID = null;
    protected ?int $level = null;
    protected ?int $combatPositionID = null;
    protected ?int $enemyTypeID = null;


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
            'minion_id' => $this->minionID ?: $this->getMinion()->id,
            'level' => $this->level ?: $this->getMinion()->level,
            'combat_position_id' => $this->combatPositionID ?: $this->getMinion()->combat_position_id,
            'enemy_type_id' => $this->enemyTypeID ?: $this->getMinion()->enemy_type_id,
            'starting_health' => rand(400, 10000),
            'starting_stamina' => rand(5000, 20000),
            'starting_mana' => rand(5000, 20000),
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

    protected function getMinion()
    {
        if ($this->minion) {
            return $this->minion;
        }
        $this->minion = MinionFactory::new()->create();
        return $this->minion;
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

    public function withLevel(int $level)
    {
        $clone = clone $this;
        $clone->level = $level;
        return $clone;
    }

    public function withCombatPositionID(int $combatPositionID)
    {
        $clone = clone $this;
        $clone->combatPositionID = $combatPositionID;
        return $clone;
    }

    public function withEnemyTypeID(int $enemyTypeID)
    {
        $clone = clone $this;
        $clone->enemyTypeID = $enemyTypeID;
        return $clone;
    }
}
