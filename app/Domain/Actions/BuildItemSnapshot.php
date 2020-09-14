<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\HeroSnapshot;

class BuildItemSnapshot
{
    public const EXCEPTION_CODE_HERO_MISMATCH = 1;

    public function execute(Item $item, HeroSnapshot $heroSnapshot)
    {
        if ($item->has_items_id !== $heroSnapshot->hero_id || $item->has_items_type !== Hero::RELATION_MORPH_MAP_KEY) {
            throw new \Exception("Item is not equipped by hero of hero snapshot", self::EXCEPTION_CODE_HERO_MISMATCH);
        }


    }
}
