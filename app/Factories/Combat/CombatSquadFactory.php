<?php


namespace App\Factories\Combat;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Models\SquadRank;
use App\Factories\Models\SquadFactory;
use Illuminate\Support\Collection;

class CombatSquadFactory
{
    protected ?SquadFactory $squadFactory = null;
    protected ?Collection $combatHeroFactories = null;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $squadFactory = $this->squadFactory ?: new SquadFactory();
        $squad = $squadFactory->create();

        $combatHeroFactories = $this->combatHeroFactories ?: collect();
        $combatHeroes = $combatHeroFactories->map(function (CombatHeroFactory $combatHeroFactory) use ($squad) {
            return $combatHeroFactory->forSquad($squad)->create();
        });

        return new CombatSquad(
            $squad->name,
            $squad->uuid,
            SquadRank::getStarting()->id,
            new AbstractCombatantCollection($combatHeroes)
        );
    }

    public function withCombatHeroFactories(Collection $combatHeroFactories = null)
    {
        if (! $combatHeroFactories) {
            $heroCount = rand(4, 6);
            $combatHeroFactories = collect();
            foreach(range(1, $heroCount) as $count) {
                $combatHeroFactories->push(CombatHeroFactory::new());
            }
        }
        $clone = clone $this;
        $clone->combatHeroFactories = $combatHeroFactories;
        return $clone;
    }
}
