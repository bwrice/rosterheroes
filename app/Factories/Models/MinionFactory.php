<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Support\Str;

class MinionFactory
{
    /** @var string|null */
    protected $enemyTypeName;

    /** @var string|null */
    protected $combatPositionName;

    public static function new(): self
    {
        return app(self::class);
    }

    public function create(array $extra = [])
    {
        /** @var Minion $minion */
        $minion = Minion::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Minion ' . rand(1, 99999),
            'config_path' => '/Yaml/Minions/test_minion.yaml'
        ], $extra));

        return $minion->fresh();
    }

    protected function getEnemyType()
    {
        if ($this->enemyTypeName) {
            $enemyType = EnemyType::query()->where('name', '=', $this->enemyTypeName)->first();
        } else {
            $enemyType = EnemyType::query()->inRandomOrder()->first();
        }
        /** @var EnemyType $enemyType */
        return $enemyType;
    }

    protected function getCombatPosition()
    {
        if ($this->combatPositionName) {
            $combatPosition = CombatPosition::query()->where('name', '=', $this->combatPositionName)->first();
        } else {
            $combatPosition = CombatPosition::query()->inRandomOrder()->first();
        }
        /** @var CombatPosition $combatPosition */
        return $combatPosition;
    }


    public function withEnemyType(string $enemyTypeName)
    {
        $clone = clone $this;
        $this->enemyTypeName = $enemyTypeName;
        return $clone;
    }

    public function withCombatPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $this->combatPositionName = $combatPositionName;
        return $clone;
    }
}
