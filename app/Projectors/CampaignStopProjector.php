<?php

namespace App\Projectors;

use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class CampaignStopProjector implements Projector
{
    use ProjectsEvents;

    public function onEventHappened(EventHappened $event)
    {
    }
}
