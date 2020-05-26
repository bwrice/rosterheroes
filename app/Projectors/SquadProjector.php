<?php

namespace App\Projectors;

use App\Domain\Models\HeroPostType;
use App\Domain\Models\Spell;
use App\StorableEvents\SpellAddedToLibrary;
use App\Domain\Models\Squad;
use App\StorableEvents\SquadExperienceIncreased;
use App\StorableEvents\SquadHeroPostAdded;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadProjector implements Projector
{
    use ProjectsEvents;

    public function onSpellAddedToLibrary(SpellAddedToLibrary $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $spell = Spell::query()->findOrFail($event->spellID);
        $squad->spells()->save($spell);
    }

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->experience += $event->amount;
        $squad->save();
    }

    public function onSquadHeroPostAdded(SquadHeroPostAdded $event, string $aggregateUuid)
    {
        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostType::query()->where('name', '=', $event->heroPostTypeName)->first();
        $squad = Squad::findUuid($aggregateUuid);
        $squad->heroPosts()->create([
            'hero_post_type_id' => $heroPostType->id
        ]);
    }
}
