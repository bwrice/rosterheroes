<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/7/19
 * Time: 9:47 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\StatType;

class StatAmountDTO
{
    /**
     * @var StatType
     */
    private $statType;
    /**
     * @var float
     */
    private $amount;

    public function __construct(StatType $statType, float $amount)
    {
        $this->statType = $statType;
        $this->amount = $amount;
    }

    /**
     * @return StatType
     */
    public function getStatType(): StatType
    {
        return $this->statType;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}