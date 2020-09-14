<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemSnapshot;
use App\HeroSnapshot;
use Illuminate\Support\Str;

class BuildItemSnapshot
{
    public const EXCEPTION_CODE_HERO_MISMATCH = 1;

    public function execute(Item $item, HeroSnapshot $heroSnapshot)
    {
        if ($item->has_items_id !== $heroSnapshot->hero_id || $item->has_items_type !== Hero::RELATION_MORPH_MAP_KEY) {
            throw new \Exception("Item is not equipped by hero of hero snapshot", self::EXCEPTION_CODE_HERO_MISMATCH);
        }

        /** @var ItemSnapshot $itemSnapshot */
        $itemSnapshot = $heroSnapshot->itemSnapshots()->create([
            'uuid' => Str::uuid(),
            'item_id' => $item->id,
            'item_type_id' => $item->item_type_id,
            'material_id' => $item->material_id,
            'name' => $item->name
        ]);

        return $itemSnapshot;
    }
}
