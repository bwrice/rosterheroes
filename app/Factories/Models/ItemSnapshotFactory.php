<?php


namespace App\Factories\Models;


use App\Domain\Models\Item;
use App\Domain\Models\ItemSnapshot;
use Illuminate\Support\Str;

class ItemSnapshotFactory
{
    protected ?Item $item = null;
    protected ?int $heroSnapshotID = null;
    protected ?int $materialID = null;
    protected ?int $itemTypeID = null;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $item = $this->getItem();

        /** @var ItemSnapshot $itemSnapshot */
        $itemSnapshot = ItemSnapshot::query()->create(array_merge([
            'uuid' => Str::uuid(),
            'item_id' => $item->id,
            'hero_snapshot_id' => $this->getHeroSnapshotID(),
            'item_type_id' => $this->getItemTypeID(),
            'material_id' => $this->getMaterialID(),
            'name' => $item->name,
            'weight' => rand(10, 200),
            'protection' => rand(0, 500),
            'block_chance' => round(rand(0, 3000)/100, 2),
            'value' => rand(50, 1000)
        ], $extra));
        return $itemSnapshot;
    }

    public function withHeroSnapshotID(int $heroSnapshotID)
    {
        $clone = clone $this;
        $clone->heroSnapshotID = $heroSnapshotID;
        return $clone;
    }

    public function withMaterialID(int $materialID)
    {
        $clone = clone $this;
        $clone->materialID = $materialID;
        return $clone;
    }

    public function withItemTypeID(int $itemTypeID)
    {
        $clone = clone $this;
        $clone->itemTypeID = $itemTypeID;
        return $clone;
    }

    protected function getHeroSnapshotID()
    {
        if ($this->heroSnapshotID) {
            return $this->heroSnapshotID;
        }

        return HeroSnapshotFactory::new()->create()->id;
    }

    protected function getMaterialID()
    {
        return $this->materialID ?: $this->getItem()->material_id;
    }

    protected function getItemTypeID()
    {
        return $this->itemTypeID ?: $this->getItem()->item_type_id;
    }

    protected function getItem()
    {
        if ($this->item) {
            return $this->item;
        }

        return ItemFactory::new()->create();
    }
}
