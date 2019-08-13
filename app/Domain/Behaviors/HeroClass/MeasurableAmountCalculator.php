<?php


namespace App\Domain\Behaviors\HeroClass;


class MeasurableAmountCalculator
{
    /**
     * @var string
     */
    private $measurableTypeName;
    /**
     * @var int
     */
    private $baseAmount;
    /**
     * @var callable
     */
    private $costToRaise;

    public function __construct(string $measurableTypeName, int $baseAmount, callable $costToRaise)
    {
        $this->measurableTypeName = $measurableTypeName;
        $this->baseAmount = $baseAmount;
        $this->costToRaise = $costToRaise;
    }

    /**
     * @return string
     */
    public function getMeasurableTypeName(): string
    {
        return $this->measurableTypeName;
    }

    /**
     * @return int
     */
    public function getBaseAmount(): int
    {
        return $this->baseAmount;
    }
}
