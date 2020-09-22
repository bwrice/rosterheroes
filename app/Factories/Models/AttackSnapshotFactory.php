<?php


namespace App\Factories\Models;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\HasAttackSnapshots;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Str;

class AttackSnapshotFactory
{
    protected ?int $attackID = null;
    protected ?HasAttackSnapshots $attacker = null;
    protected ?ResourceCostsCollection $resourceCosts = null;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var AttackSnapshot $attackSnapshot */
        $attackSnapshot = $this->getAttacker()
            ->attackSnapshots()
            ->create(array_merge([
                'uuid' => Str::uuid(),
                'attack_id' => $this->getAttackID(),
                'damage' => rand(10, 300),
                'combat_speed' => round(rand(500, 10000) / 100, 2),
                'name' => 'Test Attack Snapshot',
                'attacker_position_id' => CombatPosition::query()->inRandomOrder()->first()->id,
                'target_position_id' => CombatPosition::query()->inRandomOrder()->first()->id,
                'damage_type_id' => DamageType::query()->inRandomOrder()->first()->id,
                'target_priority_id' => TargetPriority::query()->inRandomOrder()->first()->id,
                'tier' => rand(1, 6),
                'targets_count' => null,
                'resource_costs' => $this->getResourceCosts()
            ], $extra));
        return $attackSnapshot;
    }

    protected function getAttackID()
    {
        if ($this->attackID) {
            return $this->attackID;
        }

        return AttackFactory::new()->create()->id;
    }

    protected function getAttacker()
    {
        if ($this->attacker) {
            return $this->attacker;
        }

        return ItemSnapshotFactory::new()->create();
    }

    protected function getResourceCosts()
    {
        if ($this->resourceCosts) {
            return $this->resourceCosts;
        }
        return new ResourceCostsCollection();
    }

    public function withResourceCosts(ResourceCostsCollection $resourceCostsCollection = null)
    {
        $clone = clone $this;
        if ($resourceCostsCollection) {
            $clone->resourceCosts = $resourceCostsCollection;
            return $clone;
        }

        $resourceCosts = new ResourceCostsCollection();

        for ($i = 1; $i <= rand(1, 3); $i++) {
            $resourceTypeName = rand(0,1) == 1 ? MeasurableType::STAMINA : MeasurableType::MANA;
            if (rand(0,1) == 1) {
                $resourceCost = new FixedResourceCost($resourceTypeName, rand(10, 100));
            } else {
                $resourceCost = new PercentResourceCost($resourceTypeName, rand(5, 20));
            }
            $resourceCosts->push($resourceCost);
        }

        $clone->resourceCosts = $resourceCosts;
        return $clone;
    }
}
