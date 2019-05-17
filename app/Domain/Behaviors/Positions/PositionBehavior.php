<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/16/19
 * Time: 7:29 PM
 */

namespace App\Domain\Behaviors\Positions;


class PositionBehavior
{
    /**
     * @var int
     */
    private $defaultSalary;

    public function __construct(int $defaultSalary)
    {
        $this->defaultSalary = $defaultSalary;
    }

    /**
     * @return int
     */
    public function getDefaultSalary(): int
    {
        return $this->defaultSalary;
    }
}