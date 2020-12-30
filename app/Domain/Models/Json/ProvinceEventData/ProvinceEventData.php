<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Jsonable;

abstract class ProvinceEventData implements Jsonable
{
    protected Province $province;
    protected CarbonInterface $happenedAt;

    public function __construct(Province $province, CarbonInterface $happenedAt)
    {
        $this->province = $province;
        $this->happenedAt = $happenedAt;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->getDataArray(), $options);
    }

    abstract protected function getDataArray();
}
