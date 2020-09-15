<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Facades\CurrentWeek;
use Illuminate\Support\Str;

class BuildSquadSnapshot extends BuildWeeklySnapshot
{
    /**
     * @var BuildHeroSnapshot
     */
    protected $buildHeroSnapshot;

    public function __construct(BuildHeroSnapshot $buildHeroSnapshot)
    {
        $this->buildHeroSnapshot = $buildHeroSnapshot;
    }

    /**
     * @param Squad $squad
     * @return SquadSnapshot
     * @throws \Exception
     */
    public function handle(Squad $squad): SquadSnapshot
    {
        /** @var SquadSnapshot $squadSnapshot */
        $squadSnapshot = SquadSnapshot::query()->create([
            'uuid' => Str::uuid(),
            'week_id' => CurrentWeek::id(),
            'squad_id' => $squad->id,
            'squad_rank_id' => $squad->squad_rank_id,
            'experience' => $squad->experience
        ]);

        $squad->heroes->each(function (Hero $hero) use ($squadSnapshot) {
            $this->buildHeroSnapshot->execute($squadSnapshot, $hero);
        });

        return $squadSnapshot;
    }
}
