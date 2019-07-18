<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\StorableEvents\CampaignCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class CampaignProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
         CampaignCreated::class => 'onCampaignCreationRequested',
    ];

    public function onCampaignCreationRequested(CampaignCreated $event)
    {
        Campaign::create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'campaignUuid';
    }
}