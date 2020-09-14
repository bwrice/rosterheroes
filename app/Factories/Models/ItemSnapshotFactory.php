<?php


namespace App\Factories\Models;


use App\Domain\Models\Item;
use App\Domain\Models\ItemSnapshot;
use Illuminate\Support\Str;

class ItemSnapshotFactory
{
    /** @var Item|null */
    protected $item;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $item = $this->getItem();

        /** @var ItemSnapshot $attackSnapshot */
        $attackSnapshot = ItemSnapshot::query()->create(array_merge([
            'uuid' => Str::uuid(),
            'item_id' => $item->id,
            'hero_snapshot_id' => HeroSnapshotFactory::new()->create()->id,
            'item_type_id' => $item->item_type_id,
            'material_id' => $item->material_id,
            'name' => $item->name
        ], $extra));
        return $attackSnapshot;
    }

    protected function getItem()
    {
        if ($this->item) {
            return $this->item;
        }

        return ItemFactory::new()->create();
    }
}
