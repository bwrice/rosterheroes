<?php

namespace App\Projectors;

use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use App\StorableEvents\SquadHeroPostAdded;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadHeroPostsProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadHeroPostAdded(SquadHeroPostAdded $event, string $aggregateUuid)
    {
        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostType::query()->where('name', '=', $event->heroPostTypeName)->first();
        $squad = Squad::uuid($aggregateUuid);
        $squad->heroPosts()->create([
            'hero_post_type_id' => $heroPostType->id
        ]);
    }
}