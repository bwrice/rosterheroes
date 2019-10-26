<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 * @package App\Http\Resources
 *
 * @mixin Item
 */
class ItemResource extends JsonResource
{
    /** @var UsesItems|null */
    public $usesItems;

    /** @var HasSlots|null */
    public $hasSlots;

    /** @var Item|mixed */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->usesItems) {
            /** @var Item $this->resource */
            $this->resource->setUsesItems($this->usesItems);
        }

        if ($this->hasSlots) {
            /** @var Item $this->resource */
            $this->resource->setHasSlots($this->hasSlots);
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->getItemName(),
            'itemClass' => new ItemClassResource($this->itemClass),
            'itemType' => new ItemTypeResource($this->itemType),
            'material' => new MaterialResource($this->material),
            'attacks' => AttackResource::collection($this->attacks)->collection->each(function (AttackResource $attackResource) {
                $attackResource->setHasAttacks($this->resource);
            }),
            'enchantments' => EnchantmentResource::collection($this->enchantments),
            'weight' => $this->weight(),
            'protection' => $this->getProtection(),
            'blockChance' => round($this->getBlockChance(), 2),
            'value' => $this->getValue()
        ];
    }

    /**
     * @param UsesItems|null $usesItems
     * @return ItemResource
     */
    public function setUsesItems(?UsesItems $usesItems): ItemResource
    {
        $this->usesItems = $usesItems;
        return $this;
    }

    /**
     * @param HasSlots|null $hasSlots
     * @return ItemResource
     */
    public function setHasSlots(?HasSlots $hasSlots): ItemResource
    {
        $this->hasSlots = $hasSlots;
        return $this;
    }
}
