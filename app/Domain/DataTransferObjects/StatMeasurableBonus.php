<?php


namespace App\Domain\DataTransferObjects;


use App\Domain\Models\Measurable;
use App\Domain\Models\StatType;

class StatMeasurableBonus
{
    /**
     * @var StatType
     */
    protected $statType;

    /**
     * @var Measurable
     */
    protected $measurable;

    public function __construct(StatType $statType, Measurable $measurable)
    {
        $this->statType = $statType;
        $this->measurable = $measurable;
    }

    /**
     * @return StatType
     */
    public function getStatType(): StatType
    {
        return $this->statType;
    }

    /**
     * @return Measurable
     */
    public function getMeasurable(): Measurable
    {
        return $this->measurable;
    }

    /**
     * @return float
     */
    public function getPercentModifier()
    {
        return round($this->getMeasurable()->getFantasyPointsModifier() * 100, 2);
    }

}
