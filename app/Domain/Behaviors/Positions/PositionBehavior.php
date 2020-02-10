<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/16/19
 * Time: 7:29 PM
 */

namespace App\Domain\Behaviors\Positions;


abstract class PositionBehavior
{
    /** @var int */
    protected $positionValue;
    /** @var int */
    protected $gamesPerSeason;
    /** @var string */
    protected $abbreviation;
    /** @var array */
    protected $factoryStatTypeNames = [];

    /**
     * @return int
     */
    public function getPositionValue(): int
    {
        return $this->positionValue;
    }

    public function getDefaultEssenceCost()
    {
        return (int) $this->getPositionValue() * 100;
    }

    public function getMinimumEssenceCost()
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

    /**
     * @return array
     */
    public function getFactoryStatTypeNames(): array
    {
        return $this->factoryStatTypeNames;
    }
}
