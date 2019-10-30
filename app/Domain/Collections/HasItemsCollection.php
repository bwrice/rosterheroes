<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\HasItems;
use Illuminate\Support\Collection;

class HasItemsCollection extends Collection
{
    public function removeDuplicates()
    {
        return $this->unique(function (HasItems $hasItems) {
            return $hasItems->getUniqueIdentifier();
        });
    }
}
