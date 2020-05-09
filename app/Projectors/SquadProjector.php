<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Spell;
use App\StorableEvents\CampaignCreated;
use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\SpellAddedToLibrary;
use App\StorableEvents\SquadCreated;
use App\Domain\Models\Squad;
use App\StorableEvents\SquadEssenceIncreased;
use App\StorableEvents\SquadExperienceIncreased;
use App\StorableEvents\SquadFavorIncreased;
use App\StorableEvents\SquadGoldDecreased;
use App\StorableEvents\SquadGoldIncreased;
use App\StorableEvents\SquadHeroPostAdded;
use App\StorableEvents\SquadLocationUpdated;
use Illuminate\Support\Str;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadCreated(SquadCreated $event, string $aggregateUuid)
    {
        $squad = Squad::query()->create([
            'uuid' => $aggregateUuid,
            'user_id' => $event->userID,
            'name' => $event->name,
            'squad_rank_id' => $event->squadRankID,
            'mobile_storage_rank_id' => $event->mobileStorageRankID,
            'province_id' => $event->provinceID
        ]);
        return $squad;
    }

    public function onLocationUpdated(SquadLocationUpdated $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->province_id = $event->toProvinceID;
        $squad->save();
    }

    public function onSpellAddedToLibrary(SpellAddedToLibrary $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $spell = Spell::query()->findOrFail($event->spellID);
        $squad->spells()->save($spell);
    }

    public function onSquadGoldIncreased(SquadGoldIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->gold += $event->amount;
        $squad->save();
    }

    public function onSquadGoldDecreased(SquadGoldDecreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->gold -= $event->amount;
        $squad->save();
    }

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->experience += $event->amount;
        $squad->save();
    }

    public function onSquadFavorIncreased(SquadFavorIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->favor += $event->amount;
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

    public function onSquadEssenceIncreased(SquadEssenceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->spirit_essence += $event->amount;
        $squad->save();
    }
}
