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

    public function addHeroPost(HeroPostType $heroPostType)
    {
        $this->recordThat(new SquadHeroPostAdded($heroPostType));

        return $this;
    }

    public function addSlots(SlotType $slotType, int $count)
    {
        $this->recordThat(new SquadSlotsAdded($slotType, $count));

        return $this;
    }

}
