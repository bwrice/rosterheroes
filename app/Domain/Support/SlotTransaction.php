<?php


namespace App\Domain\Support;


use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Item;
use Illuminate\Contracts\Support\Arrayable;

class SlotTransaction implements Arrayable
{
    public const TYPE_EMPTY = 'empty';
    public const TYPE_FILL = 'fill';

    /**
     * @var SlotCollection
     */
    private $slots;
    /**
     * @var HasSlots
     */
    private $hasSlots;
    /**
     * @var string
     */
    private $type;
    /**
     * @var Item
     */
    private $item;

    public function __construct(SlotCollection $slots, HasSlots $hasSlots, Item $item, string $type)
    {
        $this->slots = $slots;
        $this->hasSlots = $hasSlots;
        $this->item = $item;
        $this->type = $type;
    }

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    /**
     * @return HasSlots
     */
    public function getHasSlots(): HasSlots
    {
        return $this->hasSlots;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'slots' => $this->slots,
            'item' => $this->item,
            'hasSlots' => $this->hasSlots,
            'type' => $this->type
        ];
    }
}
