<?php

namespace App\Aggregates;

use App\Domain\Models\HeroPostType;
use App\Domain\Models\SlotType;
use App\StorableEvents\SquadCreated;
use App\Events\SquadEssenceIncreased;
use App\Events\SquadFavorIncreased;
use App\Events\SquadGoldIncreased;
use App\StorableEvents\SquadHeroPostAdded;
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

    public function increaseFavor(int $amount)
    {
        $this->recordThat(new SquadFavorIncreased($amount));

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

}
