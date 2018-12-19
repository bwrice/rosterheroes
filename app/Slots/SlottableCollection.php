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
    public function removeFromSlots()
    {
        $this->each(function (Slottable $slottable) {
           $slottable->removeFromSlots();
        });
        return $this;
    }
}