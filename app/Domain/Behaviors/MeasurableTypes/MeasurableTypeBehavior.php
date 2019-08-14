<?php


namespace App\Domain\Behaviors\MeasurableTypes;


class MeasurableTypeBehavior
{
    /**
     * @var string
     */
    private $measurableGroup;
    /**
     * @var int
     */
    private $baseAmount;

    public function __construct(string $measurableGroup, int $baseAmount)
    {
        $this->measurableGroup = $measurableGroup;
        $this->baseAmount = $baseAmount;
    }

    /**
     * @return string
     */
    public function getMeasurableGroup(): string
    {
        return $this->measurableGroup;
    }

    /**
     * @return int
     */
    public function getBaseAmount(): int
    {
        return $this->baseAmount;
    }

    public function getCostToRaiseCoefficient(): float
    {
        //TODO
        return 50;
    }
}
