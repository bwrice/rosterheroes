<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Facades\WeekService;
use Illuminate\Support\Str;

class BuildSquadSnapshot
{
    public const EXCEPTION_CODE_SNAPSHOT_EXISTS = 1;
    public const EXCEPTION_CODE_WEEK_NOT_FINALIZING = 2;

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
     * @param Week $week
     * @return SquadSnapshot
     * @throws \Exception
     */
    public function execute(Squad $squad): SquadSnapshot
    {
        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Cannot create squad snapshot when week is not finalizing", self::EXCEPTION_CODE_WEEK_NOT_FINALIZING);
        }

        $existingSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $squad->id)
            ->where('week_id', '=', CurrentWeek::id())
            ->first();

        if ($existingSnapshot) {
            throw new \Exception("Snapshot for squad and week already exists", self::EXCEPTION_CODE_SNAPSHOT_EXISTS);
        }

        /** @var SquadSnapshot $squadSnapshot */
        $squadSnapshot = SquadSnapshot::query()->create([
            'uuid' => Str::uuid(),
            'week_id' => CurrentWeek::id(),
            'squad_id' => $squad->id,
            'squad_rank_id' => $squad->squad_rank_id,
            'experience' => $squad->experience
        ]);

        $squad->heroes->combatReady()->each(function (Hero $hero) use ($squadSnapshot) {
            $this->buildHeroSnapshot->execute($squadSnapshot, $hero);
        });

        return $squadSnapshot;
    }
}
