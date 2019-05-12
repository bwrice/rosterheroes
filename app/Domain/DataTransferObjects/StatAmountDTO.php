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
    private $stat;
    /**
     * @var float
     */
    private $amount;

    public function __construct(StatType $stat, float $amount)
    {
        $this->stat = $stat;
        $this->amount = $amount;
    }
}