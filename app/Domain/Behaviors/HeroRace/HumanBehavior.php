<?php


namespace App\Domain\Behaviors\HeroRace;


class HumanBehavior extends HeroRaceBehavior
{

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroRaces/human.svg');
    }

    public function getIconAlt(): string
    {
        return '';
    }
}
