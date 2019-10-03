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
    abstract public function getIconSVG(): string;
}
