<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;

abstract class ProvinceEventData
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

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
