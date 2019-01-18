<?php

namespace App\Projectors;

use App\Events\SquadHeroPostAdded;
use App\HeroRace;
use App\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadHeroPostsProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadHeroPostAdded::class => 'onSquadHeroPostAdded'
    ];

    public function onSquadHeroPostAdded(SquadHeroPostAdded $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $heroRace = HeroRace::findOrFail($event->heroRaceID);
        $squad->heroPosts()->create([
           'hero_race_id' => $heroRace->id
        ]);
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}