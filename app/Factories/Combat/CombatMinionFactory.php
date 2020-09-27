<?php


namespace App\Factories\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\CombatPosition;
use App\Factories\Models\MinionFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatMinionFactory extends AbstractCombatantFactory
{
    protected $minionCombatAttackFactories;

    /**
     * @return CombatMinion
     */
    public function create()
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = $this->getCombatPosition();

        $combatAttacks = collect();
        if ($this->minionCombatAttackFactories) {
            $combatAttacks = $this->minionCombatAttackFactories->map(function (MinionCombatAttackFactory $minionCombatAttackFactory) {
                return $minionCombatAttackFactory->create();
            });
        }

        return new CombatMinion(
            (string) MinionFactory::new()->create()->uuid,
            (string) Str::uuid(),
            1500,
            250,
            20,
            $combatPosition,
            new CombatAttackCollection($combatAttacks)
        );
    }

    public function withMinionCombatAttacks(Collection $minionCombatAttackFactories = null)
    {
        if (! $minionCombatAttackFactories) {
            $minionCombatAttackFactories = collect();
            foreach (range(1, rand(1, 4)) as $item) {
                $minionCombatAttackFactories->push(MinionCombatAttackFactory::new());
            }
        }

        $clone = clone $this;
        $clone->minionCombatAttackFactories = $minionCombatAttackFactories;
        return $clone;
    }
}
