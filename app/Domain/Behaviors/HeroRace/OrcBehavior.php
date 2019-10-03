<?php


namespace App\Domain\Behaviors\HeroRace;


class OrcBehavior extends HeroRaceBehavior
{
    public function __construct()
    {
        $this->vueComponentName = 'OrcSVG';
    }
}
