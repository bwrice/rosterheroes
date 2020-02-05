<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Combat\CombatSquad;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\TargetPriority;
use App\Facades\HeroService;
use Illuminate\Support\Collection;

class BuildCombatSquadAction
{
    /**
     * @var BuildCombatHeroAction
     */
    protected $buildCombatHeroAction;

    public function __construct(BuildCombatHeroAction $buildCombatHeroAction)
    {
        $this->buildCombatHeroAction = $buildCombatHeroAction;
    }

    /**
     * @param Squad $squad
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return CombatSquad
     */
    public function execute(Squad $squad, ?Collection $combatPositions, ?Collection $targetPriorities, ?Collection $damageTypes): CombatSquad
    {
        $combatPositions ?: CombatPosition::all();
        $targetPriorities ?: TargetPriority::all();
        $damageTypes ?: DamageType::all();

        $heroes = $squad->heroes->filter(function (Hero $hero) {
            return HeroService::combatReady($hero);
        });
        if ($heroes->isEmpty()) {
            throw new \RuntimeException("No combat ready heroes");
        }
        $combatHeroes = new CombatantCollection();
        $heroes->each(function (Hero $hero) use ($combatHeroes, $combatPositions, $targetPriorities, $damageTypes) {
            $combatHeroes->push($this->buildCombatHeroAction->execute($hero, $combatPositions, $targetPriorities, $damageTypes));
        });
        return new CombatSquad($squad->id, $squad->experience, $combatHeroes);
    }
}
