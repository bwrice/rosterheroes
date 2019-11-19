<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignCreated;
use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\SpellAddedToLibrary;
use App\StorableEvents\SquadCreated;
use App\StorableEvents\SquadEssenceIncreased;
use App\StorableEvents\SquadExperienceIncreased;
use App\StorableEvents\SquadFavorIncreased;
use App\StorableEvents\SquadGoldDecreased;
use App\StorableEvents\SquadGoldIncreased;
use App\StorableEvents\SquadHeroPostAdded;
use App\StorableEvents\SquadLocationUpdated;
use App\StorableEvents\SquadSlotsAdded;
use Spatie\EventProjector\AggregateRoot;

final class SquadAggregate extends AggregateRoot
{
    /**
     * @param int $userID
     * @param string $name
     * @param int $squadRankID
     * @param int $mobilStorageRankID
     * @param int $provinceID
     * @return $this
     */
    public function createSquad(int $userID, string $name, int $squadRankID, int $mobilStorageRankID, int $provinceID)
    {
        $this->recordThat(new SquadCreated($userID, $name, $squadRankID, $mobilStorageRankID, $provinceID));

        return $this;
    }

    public function increaseEssence(int $amount)
    {
        $this->recordThat(new SquadEssenceIncreased($amount));

        return $this;
    }

    public function increaseGold(int $amount)
    {
        $this->recordThat(new SquadGoldIncreased($amount));

        return $this;
    }

    public function decreaseGold(int $amount)
    {
        $this->recordThat(new SquadGoldDecreased($amount));

        return $this;
    }

    public function increaseFavor(int $amount)
    {
        $this->recordThat(new SquadFavorIncreased($amount));

        return $this;
    }

    public function increaseExperience(int $amount)
    {
        $this->recordThat(new SquadExperienceIncreased($amount));

        return $this;
    }

    public function addHeroPost(string $heroPostTypeName)
    {
        $this->recordThat(new SquadHeroPostAdded($heroPostTypeName));

        return $this;
    }

    public function addSlots(string $slotTypeName, int $count)
    {
        $this->recordThat(new SquadSlotsAdded($slotTypeName, $count));

        return $this;
    }

    public function updateLocation(int $fromProvinceID, int $toProvinceID)
    {
        $this->recordThat(new SquadLocationUpdated($fromProvinceID, $toProvinceID));

        return $this;
    }

    public function addSpellToLibrary(int $spellID)
    {
        $this->recordThat(new SpellAddedToLibrary($spellID));

        return $this;
    }

}
