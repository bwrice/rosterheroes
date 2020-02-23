<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\CombatHeroCollection;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\TargetPriority;
use App\Exceptions\BuildCombatSquadException;
use App\Facades\HeroService;
use Illuminate\Support\Collection;

class BuildCombatSquad
{
    /**
     * @var BuildCombatHero
     */
    protected $buildCombatHero;

    public function __construct(BuildCombatHero $buildCombatHero)
    {
        $this->buildCombatHero = $buildCombatHero;
    }

    /**
     * @param Squad $squad
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return CombatSquad
     */
    public function execute(Squad $squad, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null): CombatSquad
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $heroes = $squad->heroes->filter(function (Hero $hero) {
            return HeroService::combatReady($hero);
        });
        if ($heroes->isEmpty()) {
            throw new BuildCombatSquadException($squad, 'No heroes combat ready', BuildCombatSquadException::CODE_NO_COMBAT_READY_HEROES);
        }
        $combatHeroes = new CombatHeroCollection();
        $heroes->each(function (Hero $hero) use ($combatHeroes, $combatPositions, $targetPriorities, $damageTypes) {
            $combatHeroes->push($this->buildCombatHero->execute($hero, $combatPositions, $targetPriorities, $damageTypes));
        });
        return new CombatSquad($squad->id, $squad->experience, $combatHeroes);
    }
}
