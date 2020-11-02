<?php


namespace App\Domain\Actions;


use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\SquadSnapshot;

class ConvertSquadSnapshotIntoCombatSquad
{
    protected ConvertHeroSnapshotIntoCombatant $convertHeroSnapshotToCombatHero;

    public function __construct(ConvertHeroSnapshotIntoCombatant $convertHeroSnapshotToCombatHero)
    {
        $this->convertHeroSnapshotToCombatHero = $convertHeroSnapshotToCombatHero;
    }

    /**
     * @param SquadSnapshot $squadSnapshot
     * @return CombatSquad
     */
    public function execute(SquadSnapshot $squadSnapshot)
    {
        $combatHeroes = collect();
        $squadSnapshot->heroSnapshots->each(function (HeroSnapshot $heroSnapshot) use ($combatHeroes) {
            $combatHeroes->push($this->convertHeroSnapshotToCombatHero->execute($heroSnapshot));
        });

        return new CombatSquad(
            $squadSnapshot->uuid,
            $squadSnapshot->experience,
            $squadSnapshot->squad_rank_id,
            $combatHeroes
        );
    }
}
