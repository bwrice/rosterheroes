<?php


namespace App\Domain\Collections;


use App\Domain\Support\SlotTransaction;
use Illuminate\Support\Collection;

class SlotTransactionCollection extends Collection
{
    public function refresh()
    {
        $this->each(function (SlotTransaction $slotTransaction) {
            $slotTransaction->refresh();
        });
    }
}
