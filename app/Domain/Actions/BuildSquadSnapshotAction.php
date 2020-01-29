<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use App\Facades\HeroService;
use App\Domain\Models\SquadSnapshot;
use App\Facades\SquadService;
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
        /** @var SquadSnapshot $existingSnapshot|null */
        $existingSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $squad->id)
            ->where('week_id', '=', CurrentWeek::id())->first();

        if ($existingSnapshot) {
            return $existingSnapshot;
        }

        if (! CurrentWeek::finalizing()) {
            throw new BuildSquadSnapshotException($this->squad, "Week not in finalizing state", BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED);
        }

        if (! SquadService::combatReady($squad)) {
            $message = 'Squad: ' . $squad->name . ' is not ready for combat';
            throw new BuildSquadSnapshotException($this->squad, $message, BuildSquadSnapshotException::SQUAD_NOT_COMBAT_READY);
        }

        return DB::transaction(function () {
            /** @var SquadSnapshot $squadSnapshot */
            $squadSnapshot = SquadSnapshot::query()->create([
                'squad_id' => $this->squad->id,
                'week_id' => CurrentWeek::id(),
                'data' => []
            ]);

            $this->getCombatReadyHeroes()->each(function (Hero $hero) use ($squadSnapshot) {
                $this->buildHeroSnapshotAction->execute($squadSnapshot, $hero);
            });

            return $squadSnapshot->fresh();
        });
    }

    protected function getCombatReadyHeroes()
    {
        return $this->squad->heroes->filter(function (Hero $hero) {
            return HeroService::combatReady($hero);
        });
    }
}
