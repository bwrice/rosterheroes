<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MinionFactory
{
    /** @var string|null */
    protected $enemyTypeName;

    /** @var string|null */
    protected $combatPositionName;

    /** @var Collection */
    protected $attackFactories;

    public static function new(): self
    {
        return app(self::class);
    }

    public function create(array $extra = [])
    {
        $combatPosition = $this->getCombatPosition();
        /** @var Minion $minion */
        $minion = Minion::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Minion ' . rand(1, 99999),
            'config_path' => '/Yaml/Minions/test_minion.yaml',
            'enemy_type_id' => $this->getEnemyType()->id,
            'combat_position_id' => $combatPosition->id,
        ], $extra));

        $this->attackFactories->each(function (AttackFactory $factory) use ($minion, $combatPosition) {
            $attack = $factory->withAttackerPosition($combatPosition->name)->create();
            $minion->attacks()->save($attack);
        });

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

    /**
     * @return CombatPosition
     */
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
        $clone->enemyTypeName = $enemyTypeName;
        return $clone;
    }

    public function withCombatPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $clone->combatPositionName = $combatPositionName;
        return $clone;
    }

    public function withAttacks(Collection $attackFactories = null)
    {
        $clone = clone $this;
        if (! $attackFactories) {
            $amount = rand(1, 3);
            $attackFactories = collect();
            foreach(range(1, $amount) as $attackCount) {
                $attackFactories->push(AttackFactory::new());
            }
        }
        $clone->attackFactories = $attackFactories;
        return $clone;
    }
}
