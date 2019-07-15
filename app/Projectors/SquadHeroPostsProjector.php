<?php

namespace App\Projectors;

use App\Events\SquadHeroPostAdded;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadHeroPostsProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadHeroPostAdded(SquadHeroPostAdded $event, string $uuid)
    {
        $squad = Squad::uuid($uuid);
        $squad->heroPosts()->create([
            'hero_post_type_id' => $event->heroPostType->id
        ]);
    }
}