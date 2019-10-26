<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SlotResource
 * @package App\Http\Resources
 *
 * @mixin Slot
 */
class SlotResource extends JsonResource
{
    /** @var UsesItems|null */
    protected $usesItems;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'item' => (new ItemResource($this->item))->setUsesItems($this->usesItems)->setHasSlots($this->hero),
            'slotType' => new SlotTypeResource($this->slotType)
        ];
    }

    /**
     * @param UsesItems|null $usesItems
     * @return SlotResource
     */
    public function setUsesItems(?UsesItems $usesItems): SlotResource
    {
        $this->usesItems = $usesItems;
        return $this;
    }
}
