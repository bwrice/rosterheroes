<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;

abstract class ProvinceEventData
{
    protected Province $province;
    protected CarbonInterface $happenedAt;
    protected array $extra;

    public function __construct(Province $province, CarbonInterface $happenedAt, array $extra)
    {
        $this->province = $province;
        $this->happenedAt = $happenedAt;
        $this->extra = $extra;
    }
}
