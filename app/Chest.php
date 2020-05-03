<?php

namespace App;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\Morphable;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;

/**
 * Class Chest
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $gold
 * @property int $quality
 * @property int $size
 * @property CarbonInterface $opened_at
 * @property int $squad_id
 * @property int|null $chest_blueprint_id
 * @property string|null $source_type
 * @property string|null $source_id
 * @property string|null $description
 *
 * @property Squad $squad
 * @property RewardsChests $source
 * @property ChestBlueprint|null $chestBlueprint
 *
 * @property ItemCollection $items
 */
class Chest extends EventSourcedModel implements HasItems
{
    const RELATION_MORPH_MAP_KEY = 'chests';

    protected $dates = [
        'opened_at',
        'updated_at',
        'created_at'
    ];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function chestBlueprint()
    {
        return $this->belongsTo(ChestBlueprint::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public static function unopenedResourceRelations()
    {
        return [
            'source'
        ];
    }

    public function getDescription()
    {
        if ($this->description) {
            return $this->description;
        }
        return ucfirst($this->getSizeDescription() . ', ' . $this->getQualityDescription() . ' chest');
    }

    public function getSizeDescription()
    {
        switch ($this->size) {
            case 1:
                return 'tiny';
            case 2:
                return 'small';
            case 3:
                return 'medium';
            case 4:
                return 'large';
            case 5:
                return 'very large';
            case 6:
                return 'gigantic';
            case 7:
                return 'colossal';
        }
        return '';
    }

    public function getQualityDescription()
    {
        switch ($this->quality) {
            case 1:
                return 'damaged';
            case 2:
                return 'rusty';
            case 3:
                return 'fair';
            case 4:
                return 'polished';
            case 5:
                return 'exquisite';
            case 6:
                return 'scintillating';
        }
        return '';
    }

    public function getMorphType(): string
    {
        return self::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public function getBackupHasItems(): ?HasItems
    {
        return null;
    }

    public function hasRoomForItem(Item $item): bool
    {
        return true;
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        return new ItemCollection();
    }

    public function getTransactionIdentification(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->getMorphType()
        ];
    }
}
