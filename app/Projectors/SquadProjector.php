<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\Domain\Models\Spell;
use App\StorableEvents\CampaignCreated;
use App\StorableEvents\SpellAddedToLibrary;
use App\StorableEvents\SquadCreated;
use App\Domain\Models\Squad;
use App\StorableEvents\SquadLocationUpdated;
use Illuminate\Support\Str;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

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

    public function onCampaignCreated(CampaignCreated $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        Campaign::query()->create([
            'uuid' => Str::uuid(),
            'squad_id' => $squad->uuid,
            'week_id' => $event->weekID,
            'continent_id' => $event->continentID
        ]);
    }

}
