<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use Carbon\CarbonInterface;

class SquadEntersProvince extends ProvinceEventData
{
    protected int $squadID;
    protected int $goldCost;

    public function __construct(Province $province, CarbonInterface $happenedAt, int $squadID, int $goldCost)
    {
        parent::__construct($province, $happenedAt);
        $this->squadID = $squadID;
        $this->goldCost = $goldCost;
    }

    /**
     * @return int
     */
    public function squadID()
    {
        return $this->squadID;
    }

    /**
     * @return int
     */
    public function getGoldCost(): int
    {
        return $this->goldCost;
    }

    protected function getDataArray()
    {
        return [
            'squad_id' => $this->squadID,
            'gold_cost' => $this->goldCost
        ];
    }
}
