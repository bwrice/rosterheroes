<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/5/19
 * Time: 6:15 PM
 */

namespace App\Domain\Behaviors\HeroRace;


class HeroRaceBehavior
{
    /**
     * @var string
     */
    private $iconName;

    public function __construct(string $iconName)
    {
        $this->iconName = $iconName;
    }

    /**
     * @return string
     */
    public function getIconPath()
    {
        return '/svg/icons/' . $this->iconName . '.svg';
    }
}