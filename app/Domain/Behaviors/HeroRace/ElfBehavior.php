<?php


namespace App\Domain\Behaviors\HeroRace;


class ElfBehavior extends HeroRaceBehavior
{

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroRaces/elf.svg');
    }

    public function getIconAlt(): string
    {
        return '';
    }
}
