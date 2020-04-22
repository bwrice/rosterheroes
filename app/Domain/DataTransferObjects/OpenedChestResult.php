<?php


namespace App\Domain\DataTransferObjects;


use App\Domain\Collections\ItemCollection;

class OpenedChestResult
{
    /**
     * @var int
     */
    protected $gold;
    /**
     * @var ItemCollection
     */
    protected $items;

    public function __construct(int $gold, ItemCollection $items)
    {
        $this->gold = $gold;
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getGold(): int
    {
        return $this->gold;
    }

    /**
     * @return ItemCollection
     */
    public function getItems(): ItemCollection
    {
        return $this->items;
    }
}
