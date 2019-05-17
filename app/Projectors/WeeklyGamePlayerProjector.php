<?php

namespace App\Projectors;

use App\Domain\Models\WeeklyGamePlayer;
use App\Events\WeeklyGamePlayerCreationRequested;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class WeeklyGamePlayerProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        WeeklyGamePlayerCreationRequested::class => 'onWeeklyPlayerCreationRequested'
    ];

    public function onWeeklyPlayerCreationRequested(WeeklyGamePlayerCreationRequested $event)
    {
        WeeklyGamePlayer::query()->create($event->attributes);
    }


    public function streamEventsBy()
    {
        return 'weeklyGamePlayerUuid';
    }
}