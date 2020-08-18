<?php


namespace App\Admin\Content\Sources;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;

class ChestBlueprintSource
{
    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string|null
     */
    protected $description;
    /**
     * @var int
     */
    protected $quality;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var int
     */
    protected $minGold;
    /**
     * @var int
     */
    protected $maxGold;
    /**
     * @var array
     */
    protected $itemBlueprints;

    public function __construct(
        string $uuid,
        ?string $description,
        int $quality,
        int $size,
        int $minGold,
        int $maxGold,
        array $itemBlueprints)
    {

        $this->uuid = $uuid;
        $this->description = $description;
        $this->quality = $quality;
        $this->size = $size;
        $this->minGold = $minGold;
        $this->maxGold = $maxGold;
        $this->itemBlueprints = $itemBlueprints;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getMinGold(): int
    {
        return $this->minGold;
    }

    /**
     * @return int
     */
    public function getMaxGold(): int
    {
        return $this->maxGold;
    }

    /**
     * @return array
     */
    public function getItemBlueprints(): array
    {
        return $this->itemBlueprints;
    }

    public function isSynced(ChestBlueprint $chestBlueprint)
    {
        if ($chestBlueprint->description !== $this->getDescription()) {
            return false;
        }
        if ($chestBlueprint->quality !== $this->getQuality()) {
            return false;
        }
        if ($chestBlueprint->size !== $this->getSize()) {
            return false;
        }
        if ($chestBlueprint->min_gold !== $this->getMinGold()) {
            return false;
        }
        if ($chestBlueprint->max_gold !== $this->getMaxGold()) {
            return false;
        }

        $itemBlueprintArrays = collect($this->getItemBlueprints());
        if ($chestBlueprint->itemBlueprints->count() !== count($itemBlueprintArrays)) {
            return false;
        }

        $unSyncedItemBlueprint = $chestBlueprint->itemBlueprints->first(function (ItemBlueprint $itemBlueprint) use ($itemBlueprintArrays) {
            $matchingBlueprintArray = $itemBlueprintArrays->first(function ($itemBlueprintArray) use ($itemBlueprint) {
                return $itemBlueprintArray['uuid'] === (string) $itemBlueprint->uuid;
            });
            if (! $matchingBlueprintArray) {
                return true;
            }

            $unSynced = $matchingBlueprintArray['count'] !== $itemBlueprint->pivot->count
                    // Comparing floats...
                || abs($itemBlueprint->pivot->chance - $matchingBlueprintArray['chance']) > PHP_FLOAT_EPSILON;

            return $unSynced;
        });

        return is_null($unSyncedItemBlueprint);
    }
}
