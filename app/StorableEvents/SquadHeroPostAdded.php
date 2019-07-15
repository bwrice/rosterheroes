<?php

namespace App\StorableEvents;

use App\Domain\Models\HeroPostType;
use Spatie\EventProjector\ShouldBeStored;

final class SquadHeroPostAdded implements ShouldBeStored
{
    /**
     * @var string
     */
    public $heroPostTypeName;

    public function __construct(string $heroPostTypeName)
    {
        $this->heroPostTypeName = $heroPostTypeName;
    }
}
