<?php


namespace App\Factories\Models;


use App\Domain\Models\ItemBlueprint;
use Illuminate\Support\Str;

class ItemBlueprintFactory
{
    /**
     * @var int|null
     */
    protected $chestBlueprintChance;

    /** @var int|null */
    protected $chestBlueprintCount;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $enchantmentPower = rand(1, 20);
        /** @var ItemBlueprint $blueprint */
        $blueprint = ItemBlueprint::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
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
        $clone = clone $this;
        $clone->chestBlueprintChance = $chestBlueprintChance;
        return $clone;
    }

    /**
     * @param int|null $chestBlueprintCount
     * @return ItemBlueprintFactory
     */
    public function setChestBlueprintCount(?int $chestBlueprintCount): ItemBlueprintFactory
    {
        $clone = clone $this;
        $clone->chestBlueprintCount = $chestBlueprintCount;
        return $clone;
    }

    /**
     * @return int|null
     */
    public function getChestBlueprintCount(): ?int
    {
        return $this->chestBlueprintCount;
    }
}
