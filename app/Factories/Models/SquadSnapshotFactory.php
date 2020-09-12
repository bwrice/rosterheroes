<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Stash;
use App\Domain\Models\Week;
use Illuminate\Support\Str;

class SquadSnapshotFactory
{
    /** @var int|null */
    protected $squadID;

    /** @var int|null */
    protected $weekID;

    /** @var int|null */
    protected $squadRankID;

    /** @var int|null */
    protected $experience = Squad::STARTING_EXPERIENCE;

    /** @var SquadFactory|null */
    protected $squadFactory;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var SquadSnapshot $squadSnapshot */
        $team = SquadSnapshot::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'week_id' => $this->getWeekID(),
            'squad_id' => $this->getSquadID(),
            'squad_rank_id' => $this->getSquadRankID(),
            'experience' => $this->experience
        ], $extra));
        return $team;
    }

    protected function getSquadID()
    {
        if ($this->squadID) {
            return $this->squadID;
        }

        $squadFactory = $this->squadFactory ?: SquadFactory::new();
        return $squadFactory->create()->id;
    }

    protected function getWeekID()
    {
        if ($this->weekID) {
            return $this->weekID;
        }

        return factory(Week::class)->create()->id;
    }

    protected function getSquadRankID()
    {
        if ($this->squadRankID) {
            return $this->squadRankID;
        }

        return SquadRank::getStarting()->id;
    }

    public function forSquad(SquadFactory $squadFactory)
    {
        $clone = clone $this;
        $clone->squadFactory = $squadFactory;
        return $clone;
    }

    public function withSquadID(int $squadID)
    {
        $clone = clone $this;
        $clone->squadID = $squadID;
        return $clone;
    }

    public function withWeekID(int $weekID)
    {
        $clone = clone $this;
        $clone->weekID = $weekID;
        return $clone;
    }

    public function withSquadRankID(int $squadRankID)
    {
        $clone = clone $this;
        $clone->squadRankID = $squadRankID;
        return $clone;
    }

    public function withExperience(int $experience)
    {
        $clone = clone $this;
        $clone->experience = $experience;
        return $clone;
    }
}
