<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Stash;
use Illuminate\Support\Str;

class StashFactory
{
    /** @var int|null */
    protected $squadID;

    /** @var SquadFactory|null */
    protected $squadFactory;

    /** @var Province|null */
    protected $province;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var Stash $stash */
        $stash = Stash::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'squad_id' => $this->getSquadID(),
            'province_id' => $this->getProvinceID()
        ], $extra));
        return $stash;
    }

    protected function getSquadID()
    {
        if ($this->squadID) {
            return $this->squadID;
        }

        $squadFactory = $this->squadFactory ?: SquadFactory::new();
        return $squadFactory->create()->id;
    }

    protected function getProvinceID()
    {
        $province = $this->province ?: Province::query()->inRandomOrder()->first();
        return $province->id;
    }

    public function atProvince(Province $province)
    {
        $clone = clone $this;
        $clone->province = $province;
        return $clone;
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

}
