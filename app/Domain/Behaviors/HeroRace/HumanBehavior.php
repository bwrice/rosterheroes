<?php


namespace App\Domain\Behaviors\HeroRace;


class HumanBehavior extends HeroRaceBehavior
{
    public function __construct()
    {
        $this->vueComponentName = 'HumanSVG';
    }
}
