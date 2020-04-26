<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Http\Resources\ResidenceResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StoreHouse
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 *
 * @property Squad $squad
 * @property Province $province
 * @property ResidenceType $residenceType
 *
 * @property ItemCollection $items
 */
class Residence extends Model implements HasItems
{
    const RELATION_MORPH_MAP_KEY = 'residences';

    protected $guarded = [];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function residenceType()
    {
        return $this->belongsTo(ResidenceType::class);
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public function getBackupHasItems(): ?HasItems
    {
        return $this->squad->getLocalStash();
    }

    public function hasRoomForItem(Item $item): bool
    {
        return $this->getOverCapacityCount() < 0;
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        $overCapacityCount = $this->getOverCapacityCount();
        return $overCapacityCount >= 0 ?
            $this->items()->inRandomOrder()->take($overCapacityCount + 1) : new ItemCollection();
    }

    protected function getOverCapacityCount(): int
    {
        return $this->items()->count() - $this->residenceType->getBehavior()->getMaxItemCount();
    }

    public function getMorphType(): string
    {
        return static::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public function getTransactionIdentification(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->getMorphType()
        ];
    }
}
