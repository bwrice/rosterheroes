<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;

class SquadEventsProvince extends ProvinceEventData
{
    public function __construct(Province $province, CarbonInterface $happenedAt, array $data)
    {
        parent::__construct($province, $happenedAt, $data);
    }

    /**
     * @return int
     */
    public function squadID()
    {
        return $this->data['squad_id'];
    }
}
