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
    /**
     * @var string
     */
    private $abbreviation;

    public function __construct(
        int $positionValue,
        int $gamesPerSeason,
        string $abbreviation)
    {
        $this->positionValue = $positionValue;
        $this->gamesPerSeason = $gamesPerSeason;
        $this->abbreviation = $abbreviation;
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

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }
}