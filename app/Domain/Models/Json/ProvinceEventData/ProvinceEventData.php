<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Jsonable;

abstract class ProvinceEventData implements Jsonable
{
    protected Province $province;
    protected CarbonInterface $happenedAt;
    protected array $data;

    public function __construct(Province $province, CarbonInterface $happenedAt, array $data)
    {
        $this->province = $province;
        $this->happenedAt = $happenedAt;
        $this->data = $data;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->data, $options);
    }
}
