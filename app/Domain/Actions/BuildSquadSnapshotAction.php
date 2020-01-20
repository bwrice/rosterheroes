<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use App\Facades\HeroCombat;
use App\Domain\Models\SquadSnapshot;
use Illuminate\Support\Facades\DB;

class BuildSquadSnapshotAction
{
    /** @var Squad */
    protected $squad;
    /**
     * @var BuildHeroSnapshotAction
     */
    protected $buildHeroSnapshotAction;

    public function __construct(BuildHeroSnapshotAction $buildHeroSnapshotAction)
    {
        $this->buildHeroSnapshotAction = $buildHeroSnapshotAction;
    }

    public function execute(Squad $squad): SquadSnapshot
    {
        $this->squad = $squad;
        if (! CurrentWeek::finalizing()) {
            throw new BuildSquadSnapshotException($this->squad, "Week not in finalizing state", BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED);
        }

        $combatReadyHeroes = $this->getCombatReadyHeroes();
        if ($combatReadyHeroes->isEmpty()) {
            throw new BuildSquadSnapshotException($this->squad, 'No heroes ready for combat', BuildSquadSnapshotException::CODE_NO_COMBAT_READY_HEROES);
        }

        return DB::transaction(function () use ($combatReadyHeroes) {
            /** @var SquadSnapshot $squadSnapshot */
            $squadSnapshot = SquadSnapshot::query()->create([
                'squad_id' => $this->squad->id,
                'week_id' => CurrentWeek::id(),
                'data' => []
            ]);

            $combatReadyHeroes->each(function (Hero $hero) use ($squadSnapshot) {
                $this->buildHeroSnapshotAction->execute($squadSnapshot, $hero);
            });

            return $squadSnapshot->fresh();
        });
    }

    protected function getCombatReadyHeroes()
    {
        return $this->squad->heroes->filter(function (Hero $hero) {
            return HeroCombat::ready($hero);
        });
    }
}
