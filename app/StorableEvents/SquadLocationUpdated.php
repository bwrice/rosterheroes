<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class SquadLocationUpdated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $fromProvinceID;
    /**
     * @var int
     */
    public $toProvinceID;

    public function __construct(int $fromProvinceID, int $toProvinceID)
    {
        $this->fromProvinceID = $fromProvinceID;
        $this->toProvinceID = $toProvinceID;
    }
}
