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
    private $positionValue;
    /**
     * @var int
     */
    private $gamesPerSeason;

    public function __construct(int $positionValue, int $gamesPerSeason)
    {
        $this->positionValue = $positionValue;
        $this->gamesPerSeason = $gamesPerSeason;
    }

    /**
     * @return int
     */
    public function getPositionValue(): int
    {
        return $this->positionValue;
    }

    public function getDefaultSalary()
    {
        return (int) $this->getPositionValue() * 100;
    }

    public function getMinimumSalary()
    {
        return (int) $this->getPositionValue() * 60;
    }

    /**
     * @return int
     */
    public function getGamesPerSeason(): int
    {
        return $this->gamesPerSeason;
    }
}