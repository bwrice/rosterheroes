<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use App\Facades\HeroCombat;
use App\SquadSnapshot;
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

        return DB::transaction(function () {
            /** @var SquadSnapshot $squadSnapshot */
            $squadSnapshot = SquadSnapshot::query()->create([
                'squad_id' => $this->squad->id,
                'week_id' => CurrentWeek::id(),
                'data' => []
            ]);

            $this->squad->heroes->filter(function (Hero $hero) {
                return HeroCombat::ready($hero);
            })->each(function (Hero $hero) use ($squadSnapshot) {
                $this->buildHeroSnapshotAction->execute($squadSnapshot, $hero);
            });

            return $squadSnapshot->fresh();
        });
    }
}
