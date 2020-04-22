<?php


namespace App\Domain\DataTransferObjects;


use App\Domain\Collections\HasItemsCollection;
use App\Domain\Collections\ItemCollection;

class OpenedChestResult
{
    /**
     * @var int
     */
    protected $gold;
    /**
     * @var HasItemsCollection
     */
    protected $hasItemsCollection;

    public function __construct(int $gold, HasItemsCollection $hasItemsCollection)
    {
        $this->gold = $gold;
        $this->hasItemsCollection = $hasItemsCollection;
    }

    /**
     * @return int
     */
    public function getGold(): int
    {
        return $this->gold;
    }

    /**
     * @return HasItemsCollection
     */
    public function getHasItemsCollection(): HasItemsCollection
    {
        return $this->hasItemsCollection;
    }
}
