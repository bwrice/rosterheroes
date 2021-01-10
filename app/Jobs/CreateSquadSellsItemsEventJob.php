<?php

namespace App\Jobs;

use App\Domain\Actions\ProvinceEvents\CreateSquadSellsItemsEvent;
use App\Domain\Models\Province;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSquadSellsItemsEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $squad;
    public Shop $shop;
    public Province $province;
    public int $itemsCount;
    public int $gold;
    private CarbonInterface $happenedAt;

    /**
     * CreateSquadSellsItemsEventJob constructor.
     * @param Squad $squad
     * @param Shop $shop
     * @param Province $province
     * @param int $itemsCount
     * @param int $gold
     * @param CarbonInterface $happenedAt
     */
    public function __construct(Squad $squad, Shop $shop, Province $province, int $itemsCount, int $gold, CarbonInterface $happenedAt)
    {
        $this->squad = $squad;
        $this->shop = $shop;
        $this->province = $province;
        $this->itemsCount = $itemsCount;
        $this->gold = $gold;
        $this->happenedAt = $happenedAt;
    }

    /**
     * @param CreateSquadSellsItemsEvent $createSquadSellsItemsEvent
     */
    public function handle(CreateSquadSellsItemsEvent $createSquadSellsItemsEvent)
    {
        $createSquadSellsItemsEvent->execute($this->squad, $this->shop, $this->province, $this->itemsCount, $this->gold, $this->happenedAt);
    }
}
