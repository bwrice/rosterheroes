<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\Events\CampaignCreationRequested;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class CampaignProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
         CampaignCreationRequested::class => 'onCampaignCreationRequested',
    ];

    public function onCampaignCreationRequested(CampaignCreationRequested $event)
    {
        Campaign::create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'campaignUuid';
    }
}