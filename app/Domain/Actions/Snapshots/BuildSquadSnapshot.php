<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuildSquadSnapshot extends BuildSnapshot
{
    public const EXCEPTION_CODE_SNAPSHOT_EXISTS = 7;

    protected BuildHeroSnapshot $buildHeroSnapshot;

    public function __construct(BuildHeroSnapshot $buildHeroSnapshot)
    {
        $this->buildHeroSnapshot = $buildHeroSnapshot;
    }

    /**
     * @param Squad $squad
     * @param $weekly
     * @return SquadSnapshot
     * @throws \Exception
     */
    public function handle(Squad $squad): SquadSnapshot
    {
        $weekID = $this->weekly ? CurrentWeek::id() : null;

        if ($weekID) {
            $existing = SquadSnapshot::query()
                ->where('week_id', '=', $weekID)
                ->where('squad_id', '=', $squad->id)
                ->first();

            if ($existing) {
                throw new \Exception("Cannot build weekly squad snapshot because snapshot already exists", self::EXCEPTION_CODE_SNAPSHOT_EXISTS);
            }
        }

        return DB::transaction(function () use ($squad, $weekID) {

            /** @var SquadSnapshot $squadSnapshot */
            $squadSnapshot = SquadSnapshot::query()->create([
                'uuid' => Str::uuid(),
                'week_id' => $weekID,
                'squad_id' => $squad->id,
                'squad_rank_id' => $squad->squad_rank_id,
                'experience' => $squad->experience
            ]);

            $squad->heroes->each(function (Hero $hero) use ($squadSnapshot) {
                $this->buildHeroSnapshot->execute($squadSnapshot, $hero);
            });

            return $squadSnapshot;
        });
    }
}
