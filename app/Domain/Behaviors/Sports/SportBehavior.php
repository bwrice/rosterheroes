<?php


namespace App\Domain\Behaviors\Sports;


abstract class SportBehavior
{
    protected $color = '';

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
