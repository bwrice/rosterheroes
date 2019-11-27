<?php


namespace App\Domain\Combat;


class CombatMoment
{
    public const SIDE_A = 'A';
    public const SIDE_B = 'B';

    /** @var int */
    protected $count = 1;

    /** @var string */
    protected $side = self::SIDE_A;


    public function tick()
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function toggleSide()
    {
        $this->side = ($this->side == self::SIDE_A) ? self::SIDE_B : self::SIDE_A;
    }

    public function getSide(): string
    {
        return $this->side;
    }

}

