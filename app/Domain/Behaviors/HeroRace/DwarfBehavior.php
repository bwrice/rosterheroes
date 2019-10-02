<?php


namespace App\Domain\Behaviors\HeroRace;


class DwarfBehavior extends HeroRaceBehavior
{

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroRaces/dwarf.svg');
    }

    public function getIconAlt(): string
    {
        return '';
    }
}
