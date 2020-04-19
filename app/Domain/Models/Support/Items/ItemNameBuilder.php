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
                return 'Poor';
            case 1:
                return 'Adequate';
            case 2:
                return 'Great';
            case 3:
                return 'Excellent';
            case 5:
                return 'Superb';
            case 6:
                return 'Fantastic';
            case 7:
                return 'Magnificent';
            case 8:
                return 'Remarkable';
            case 9:
            default:
                return 'Unparalleled';
        }
    }
}
