<?php


namespace App\Domain\Models\Support\Items;


use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Models\Item;

class ItemNameBuilder
{

    public function buildItemName(Item $item)
    {
        $name = '';
        $enchantments = $item->enchantments;
        if ($enchantments->isNotEmpty()) {
            $name .= $this->getItemEnchantmentQuality($enchantments) . ', ';
        }
        $name .= $item->material->name;
        $name .= ' ' . $item->itemType->name;
        return $name;
    }

    public function getItemEnchantmentQuality(EnchantmentCollection $enchantments)
    {
        // Less than 5 boost level sum automatically gets a "Fine" prefix
        $numerator = max(0, $enchantments->boostLevelSum() - 10);
        $value = (int) floor(($numerator/3)**.5);
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
                return 'Godly';
        }
    }
}
