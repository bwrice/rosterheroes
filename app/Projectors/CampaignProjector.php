<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\StorableEvents\CampaignCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class CampaignProjector implements Projector
{
    use ProjectsEvents;
}
