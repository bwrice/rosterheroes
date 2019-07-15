<?php

namespace App\Events;

use App\Domain\Models\HeroPostType;
use Spatie\EventProjector\ShouldBeStored;

class SquadHeroPostAdded implements ShouldBeStored
{
    /** @var  HeroPostType */
    public $heroPostType;

    public function __construct(HeroPostType $heroPostType)
    {
        $this->heroPostType = $heroPostType;
    }
}
