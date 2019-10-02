<?php


namespace App\Domain\Support;


use App\Domain\Models\Item;

class ItemNameBuilder
{
    /** @var Item */
    protected $item;

    public function buildItemName(Item $item)
    {
        $this->item = $item;
        $name = $this->getQualityPrefix();
        $name .= ', ' . $item->material->name;
        $name .= ' ' . $this->item->itemType->name;
        return $name;
    }

    public function getQualityPrefix()
    {
        $boostLevelSum = $this->item->enchantments->boostLevelSum();
        $value = (int) floor($boostLevelSum**.5);
        switch ($value) {
            case 0:
                return 'Simple';
            case 1:
                return 'Good';
            case 2:
                return 'Great';
            case 3:
                return 'Excellent';
            case 4:
                return 'Fantastic';
            case 5:
            default:
                return 'Magnificent';
        }
    }
}
