<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Residence;
use App\Domain\Models\ResidenceType;
use Illuminate\Support\Str;

class ResidenceFactory
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
        /** @var Residence $team */
        $team = Residence::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'residence_type_id' => ResidenceType::forName(ResidenceType::SHACK)->id,
            'squad_id' => $this->getSquadID(),
            'province_id' => $this->getProvinceID()
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

    protected function getProvinceID()
    {
        if ($this->province) {
            return $this->province->id;
        }
        return Province::query()->inRandomOrder()->first()->id;
    }

    public function withProvince(Province $province)
    {
        $clone = clone $this;
        $clone->province = $province;
        return $clone;
    }
}
