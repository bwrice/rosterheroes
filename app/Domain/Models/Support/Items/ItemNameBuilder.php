<?php


namespace App\Domain\Models\Support\Items;


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
        $value = (int) floor(($boostLevelSum/3)**.5);
        switch ($value) {
            case 0:
                return 'Fine';
            case 1:
                return 'Worthy';
            case 2:
                return 'Excellent';
            case 3:
                return 'Superb';
            case 4:
                return 'Fantastic';
            case 5:
                return 'Superior';
            case 6:
                return 'Magnificent';
            case 7:
                return 'Remarkable';
            case 8:
                return 'Unparalleled';
            case 9:
            default:
                return 'Celestial';
        }
    }
}
