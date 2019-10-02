<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/5/19
 * Time: 6:15 PM
 */

namespace App\Domain\Behaviors\HeroRace;


abstract class HeroRaceBehavior
{
    abstract public function getIconSrc(): string;

    abstract public function getIconAlt(): string;

    public function getIcon()
    {
        return [
            'src' => $this->getIconSrc(),
            'alt' => $this->getIconAlt()
        ];
    }

}
