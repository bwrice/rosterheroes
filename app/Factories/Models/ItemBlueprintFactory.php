<?php


namespace App\Factories\Models;


use App\Domain\Models\ItemBlueprint;

class ItemBlueprintFactory
{
    /**
     * @var int|null
     */
    protected $chestBlueprintChance;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $enchantmentPower = rand(1, 20);
        /** @var ItemBlueprint $blueprint */
        $blueprint = ItemBlueprint::query()->create(array_merge([
            'enchantment_power' => $enchantmentPower
        ], $extra));
        return $blueprint;
    }

    /**
     * @return int|null
     */
    public function getChestBlueprintChance(): ?int
    {
        return $this->chestBlueprintChance;
    }

    /**
     * @param int|null $chestBlueprintChance
     * @return ItemBlueprintFactory
     */
    public function setChestBlueprintChance(?int $chestBlueprintChance): ItemBlueprintFactory
    {
        $this->chestBlueprintChance = $chestBlueprintChance;
        return $this;
    }
}
