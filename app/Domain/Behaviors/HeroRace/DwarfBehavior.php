<?php


namespace App\Domain\Behaviors\HeroRace;


class DwarfBehavior extends HeroRaceBehavior
{
    public function __construct()
    {
        $this->vueComponentName = 'DwarfSVG';
    }
}
