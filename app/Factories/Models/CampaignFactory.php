<?php


namespace App\Factories\Models;



use App\Domain\Models\Campaign;
use App\Domain\Models\Continent;
use App\Domain\Models\Week;
use Illuminate\Support\Str;

class CampaignFactory
{
    /** @var int|null */
    protected $squadID;

    /** @var int|null */
    protected $weekID;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var Campaign $campaign */
        $campaign = Campaign::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'squad_id' => $this->getSquadID(),
            'week_id' => $this->getWeekID(),
            'continent_id' => Continent::query()->inRandomOrder()->first()->id
        ], $extra));
        return $campaign;
    }

    protected function getSquadID()
    {
        if ($this->squadID) {
            return $this->squadID;
        }
        return SquadFactory::new()->create()->id;
    }

    protected function getWeekID()
    {
        if ($this->weekID) {
            return $this->weekID;
        }
        return factory(Week::class)->create()->id;
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
}
