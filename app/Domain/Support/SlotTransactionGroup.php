<?php


namespace App\Domain\Support;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\StoreHouse;

class SlotTransactionGroup
{
    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var StoreHouse */
    protected $storeHouse;

    /** @var Stash */
    protected $stash;

    /**
     * @param Hero $hero
     * @return SlotTransactionGroup
     */
    public function setHero(Hero $hero): SlotTransactionGroup
    {
        $this->hero = $hero;
        return $this;
    }

    /**
     * @param Squad $squad
     * @return SlotTransactionGroup
     */
    public function setSquad(Squad $squad): SlotTransactionGroup
    {
        $this->squad = $squad;
        return $this;
    }

    /**
     * @param StoreHouse $storeHouse
     * @return SlotTransactionGroup
     */
    public function setStoreHouse(StoreHouse $storeHouse): SlotTransactionGroup
    {
        $this->storeHouse = $storeHouse;
        return $this;
    }

    /**
     * @param Stash $stash
     * @return SlotTransactionGroup
     */
    public function setStash(Stash $stash): SlotTransactionGroup
    {
        $this->stash = $stash;
        return $this;
    }
}
