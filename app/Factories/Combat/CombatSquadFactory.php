<?php


namespace App\Factories\Combat;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Factories\Models\SquadFactory;
use Illuminate\Support\Collection;

class CombatSquadFactory
{
    /** @var SquadFactory|null */
    protected $squadFactory;

    /** @var Collection */
    protected $combatHeroFactories;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $squadFactory = $this->squadFactory ?: new SquadFactory();
        $squad = $squadFactory->create();

        $combatHeroFactories = $this->combatHeroFactories ?: collect();
        $combatHeroes = $combatHeroFactories->map(function (CombatHeroFactory $combatHeroFactory) {
            return $combatHeroFactory->create();
        });
        return new CombatSquad(
            $squad->name,
            $squad->uuid,
            $squad->experience,
            new AbstractCombatantCollection($combatHeroes)
        );
    }
}
