<?php


namespace App\Admin\Content\Sources;


use App\Domain\Models\ItemBlueprint;

class ItemBlueprintSource
{
    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string|null
     */
    protected $itemName;
    /**
     * @var string|null
     */
    protected $description;
    /**
     * @var int|null
     */
    protected $enchantmentPower;
    /**
     * @var array
     */
    protected $itemBases;
    /**
     * @var array
     */
    protected $itemClasses;
    /**
     * @var array
     */
    protected $itemTypes;
    /**
     * @var array
     */
    protected $attacks;
    /**
     * @var array
     */
    protected $materials;
    /**
     * @var array
     */
    protected $enchantments;

    public function __construct(
        string $uuid,
        ?string $itemName,
        ?string $description,
        ?int $enchantmentPower,
        array $itemBases,
        array $itemClasses,
        array $itemTypes,
        array $attacks,
        array $materials,
        array $enchantments
    )
    {
        $this->uuid = $uuid;
        $this->itemName = $itemName;
        $this->description = $description;
        $this->enchantmentPower = $enchantmentPower;
        $this->itemBases = $itemBases;
        $this->itemClasses = $itemClasses;
        $this->itemTypes = $itemTypes;
        $this->attacks = $attacks;
        $this->materials = $materials;
        $this->enchantments = $enchantments;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int|null
     */
    public function getEnchantmentPower(): ?int
    {
        return $this->enchantmentPower;
    }

    /**
     * @return array
     */
    public function getItemBases(): array
    {
        return $this->itemBases;
    }

    /**
     * @return array
     */
    public function getItemClasses(): array
    {
        return $this->itemClasses;
    }

    /**
     * @return array
     */
    public function getItemTypes(): array
    {
        return $this->itemTypes;
    }

    /**
     * @return array
     */
    public function getAttacks(): array
    {
        return $this->attacks;
    }

    /**
     * @return array
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    /**
     * @return array
     */
    public function getEnchantments(): array
    {
        return $this->enchantments;
    }

    public function isSynced(ItemBlueprint $itemBlueprint)
    {
        if ($itemBlueprint->item_name !== $this->getItemName()) {
            return false;
        }
        if ($itemBlueprint->description !== $this->getDescription()) {
            return false;
        }
        if ($itemBlueprint->item_name !== $this->getItemName()) {
            return false;
        }
        if ($itemBlueprint->enchantment_power !== $this->getEnchantmentPower()) {
            return false;
        }
        if ($itemBlueprint->itemBases->pluck('id')->toArray() !== $this->getItemBases()) {
            return false;
        }
        if ($itemBlueprint->itemClasses->pluck('id')->toArray() !== $this->getItemClasses()) {
            return false;
        }
        if ($itemBlueprint->itemTypes->pluck('uuid')->toArray() !== $this->getItemTypes()) {
            return false;
        }
        if ($itemBlueprint->attacks->pluck('uuid')->toArray() !== $this->getAttacks()) {
            return false;
        }
        if ($itemBlueprint->materials->pluck('uuid')->toArray() !== $this->getMaterials()) {
            return false;
        }
        if ($itemBlueprint->enchantments->pluck('uuid')->toArray() !== $this->getEnchantments()) {
            return false;
        }
        return true;
    }
}
