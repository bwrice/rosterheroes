<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
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
    protected $usesItems;

    /** @var HasItems|null */
    protected $hasItems;

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

        if ($this->hasItems) {
            /** @var Item $this->resource */
            $this->resource->setHasItems($this->hasItems);
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->getItemName(),
            'itemClass' => new ItemClassResource($this->itemClass),
            'itemType' => new ItemTypeResource($this->itemType),
            'material' => new MaterialResource($this->material),
            'attacks' => AttackResource::collection($this->getAttacks())->collection->each(function (AttackResource $attackResource) {
                $attackResource->setHasAttacks($this->resource);
            }),
            'enchantments' => EnchantmentResource::collection($this->enchantments),
            'weight' => $this->weight(),
            'protection' => $this->getProtection(),
            'blockChance' => round($this->getBlockChance(), 2),
            'value' => $this->getValue(),
            'transaction' => $this->getTransaction(),
            'shopPrice' => $this->getShopPrice()
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
     * @param HasItems|null $hasItems
     * @return ItemResource
     */
    public function setHasItems(?HasItems $hasItems): ItemResource
    {
        $this->hasItems = $hasItems;
        return $this;
    }
}
