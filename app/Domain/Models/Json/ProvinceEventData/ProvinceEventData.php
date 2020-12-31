<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;

abstract class ProvinceEventData
{
    protected Province $province;
    protected ?Squad $squad;
    protected CarbonInterface $happenedAt;
    protected array $extra;

    public function __construct(Province $province, ?Squad $squad, CarbonInterface $happenedAt, array $extra)
    {
        $this->province = $province;
        $this->squad = $squad;
        $this->happenedAt = $happenedAt;
        $this->extra = $extra;
    }
}
