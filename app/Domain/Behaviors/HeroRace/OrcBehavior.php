<?php


namespace App\Domain\Behaviors\HeroRace;


class OrcBehavior extends HeroRaceBehavior
{

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroRaces/orc.svg');
    }

    public function getIconAlt(): string
    {
        return '';
    }
}
