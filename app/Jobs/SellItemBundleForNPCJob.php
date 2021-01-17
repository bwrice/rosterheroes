<?php

namespace App\Jobs;

use App\Domain\Actions\SellItemBundleToShop;
use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SellItemBundleForNPCJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ItemCollection $items;
    public Squad $npc;
    public Shop $shop;

    public function __construct(ItemCollection $items, Squad $npc, Shop $shop)
    {
        $this->items = $items;
        $this->npc = $npc;
        $this->shop = $shop;
    }

    /**
     * @param SellItemBundleToShop $sellItemBundleToShop
     */
    public function handle(SellItemBundleToShop $sellItemBundleToShop)
    {
        $sellItemBundleToShop->execute($this->items, $this->npc, $this->shop);
    }
}
