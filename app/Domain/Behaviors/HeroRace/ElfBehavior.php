<?php


namespace App\Domain\Behaviors\HeroRace;


class ElfBehavior extends HeroRaceBehavior
{

    public function __construct()
    {
        $this->vueComponentName = 'ElfSVG';
    }
}
