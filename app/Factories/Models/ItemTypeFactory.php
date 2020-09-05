<?php


namespace App\Factories\Models;


use App\Domain\Models\Attack;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use Illuminate\Support\Str;

class ItemTypeFactory
{
    protected $itemBaseID;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Factory ItemType ' . Str::random(10),
            'tier' => rand(1, 6),
            'item_base_id' => $this->getItemBaseID(),
        ], $extra));
        return $itemType;
    }

    protected function getItemBaseID()
    {
        if ($this->itemBaseID) {
            return $this->itemBaseID;
        }

        return ItemBase::query()->inRandomOrder()->first()->id;
    }
}
