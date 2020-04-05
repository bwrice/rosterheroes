<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MinionFactory
{
    /** @var int|null */
    protected $countForSideQuest;

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
            'level' => rand(5, 20),
            'enemy_type_id' => $this->getEnemyType()->id,
            'combat_position_id' => $combatPosition->id,
        ], $extra));

        if ($this->attackFactories) {
            $this->attackFactories->each(function (AttackFactory $factory) use ($minion, $combatPosition) {
                $attack = $factory->withAttackerPosition($combatPosition->name)->create();
                $minion->attacks()->save($attack);
            });
        }

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
        $clone->attackFactories = $attackFactories ?: $this->getDefaultAttackFactories();
        return $clone;
    }

    protected function getDefaultAttackFactories()
    {
        $amount = rand(3, 6);
        $attackFactories = collect();
        foreach(range(1, $amount) as $attackCount) {
            $attackFactories->push(AttackFactory::new());
        }
        return $attackFactories;
    }

    /**
     * @param mixed $countForSideQuest
     * @return MinionFactory
     */
    public function setCountForSideQuest(int $countForSideQuest)
    {
        $clone = clone $this;
        $clone->countForSideQuest = $countForSideQuest;
        return $clone;
    }

    /**
     * @return int|null
     */
    public function getCountForSideQuest()
    {
        return $this->countForSideQuest;
    }
}
