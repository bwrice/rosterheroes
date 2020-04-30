<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/7/19
 * Time: 9:47 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\StatType;
use Illuminate\Contracts\Support\Arrayable;

class StatAmountDTO implements Arrayable
{
    /**
     * @var StatType
     */
    protected $statType;
    /**
     * @var float
     */
    protected $amount;

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

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'stat_type' => $this->statType->toArray(),
        ];
    }

    public function getTotalPoints()
    {
        return $this->statType->getBehavior()->getTotalPoints($this->amount);
    }
}
