<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/9/18
 * Time: 5:58 PM
 */

namespace App\Slots;


use Illuminate\Database\Eloquent\Collection;

class SlottableCollection extends Collection
{
    public function getSlots()
    {
        $slotCollection = new SlotCollection();
        $this->loadMissing('slots')->each(function (Slottable $slottable) use (&$slotCollection) {
           $slotCollection = $slotCollection->merge($slottable->getSlots());
        });
        return $slotCollection;
    }
}