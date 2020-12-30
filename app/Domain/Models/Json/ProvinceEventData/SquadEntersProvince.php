<?php


namespace App\Domain\Models\Json\ProvinceEventData;


class SquadEntersProvince extends ProvinceEventData
{
    /**
     * @return string
     */
    public function squadUuid()
    {
        return $this->data['squad']['uuid'];
    }

    /**
     * @return int
     */
    public function getGoldCost()
    {
        return $this->data['gold_cost'];
    }

    /**
     * @return string
     */
    public function getProvinceLeftUuid()
    {
        return $this->data['province_left']['uuid'];
    }

}
