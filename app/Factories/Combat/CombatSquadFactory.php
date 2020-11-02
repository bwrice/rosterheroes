<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Models\SquadRank;
use App\Factories\Models\SquadFactory;
use Illuminate\Support\Collection;

class CombatSquadFactory
{
    protected ?SquadFactory $squadFactory = null;
    protected ?Collection $combatHeroFactories = null;
    protected ?Collection $combatHeroes = null;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $squadFactory = $this->squadFactory ?: new SquadFactory();
        $squad = $squadFactory->create();

        return new CombatSquad(
            $squad->uuid,
            $squad->experience,
            SquadRank::getStarting()->id,
            $this->getCombatHeroes()
        );
    }

    public function withCombatHeroFactories(Collection $combatHeroFactories = null)
    {
        if (! $combatHeroFactories) {
            $heroCount = rand(4, 6);
            $combatHeroFactories = collect();
            foreach(range(1, $heroCount) as $count) {
                $combatHeroFactories->push(CombatantFactory::new());
            }
        }
        $clone = clone $this;
        $clone->combatHeroFactories = $combatHeroFactories;
        return $clone;
    }

    public function withCombatHeroes(Collection $combatHeroes)
    {
        $clone = clone $this;
        $clone->combatHeroes = $combatHeroes;
        return $clone;
    }

    protected function getCombatHeroes()
    {
        if ($this->combatHeroes) {
            return $this->combatHeroes;
        }
        $combatHeroFactories = $this->combatHeroFactories ?: collect();
       return $combatHeroFactories->map(function (CombatantFactory $combatantFactory) {
            return $combatantFactory->create();
        });
    }
}
