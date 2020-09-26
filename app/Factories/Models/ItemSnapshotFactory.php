<?php


namespace App\Factories\Models;


use App\Domain\Models\Item;
use App\Domain\Models\ItemSnapshot;
use Illuminate\Support\Str;

class ItemSnapshotFactory
{
    protected ?Item $item = null;
    protected ?int $heroSnapshotID = null;

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
            'item_type_id' => $item->item_type_id,
            'material_id' => $item->material_id,
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

    protected function getHeroSnapshotID()
    {
        if ($this->heroSnapshotID) {
            return $this->heroSnapshotID;
        }

        return HeroSnapshotFactory::new()->create()->id;
    }

    protected function getItem()
    {
        if ($this->item) {
            return $this->item;
        }

        return ItemFactory::new()->create();
    }
}
