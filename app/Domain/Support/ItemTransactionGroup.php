<?php


namespace App\Domain\Support;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\StoreHouse;
use App\Http\Resources\HeroResource;
use App\Http\Resources\MobileStorageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemTransactionGroup extends JsonResource
{
    /** @var Hero|null */
    protected $hero;

    /** @var Squad|null */
    protected $squad;

    /** @var StoreHouse|null */
    protected $storeHouse;

    /** @var Stash|null */
    protected $stash;

    /**
     * SlotTransactionGroup constructor.
     */
    public function __construct()
    {
        parent::__construct([]);
    }

    public function setHero(Hero $hero): ItemTransactionGroup
    {
        $this->hero = $hero;
        return $this;
    }

    /**
     * @param Squad $squad
     * @return ItemTransactionGroup
     */
    public function setSquad(Squad $squad): ItemTransactionGroup
    {
        $this->squad = $squad;
        return $this;
    }

    /**
     * @param StoreHouse $storeHouse
     * @return ItemTransactionGroup
     */
    public function setStoreHouse(StoreHouse $storeHouse): ItemTransactionGroup
    {
        $this->storeHouse = $storeHouse;
        return $this;
    }

    /**
     * @param Stash $stash
     * @return ItemTransactionGroup
     */
    public function setStash(Stash $stash): ItemTransactionGroup
    {
        $this->stash = $stash;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'hero' => $this->when((bool) $this->hero, new HeroResource($this->hero->fresh()->load(Hero::heroResourceRelations()))),
            'mobileStorage' => $this->when((bool) $this->squad, new MobileStorageResource($this->squad->fresh()->load(Squad::getMobileStorageResourceRelations())))
        ];
    }
}
