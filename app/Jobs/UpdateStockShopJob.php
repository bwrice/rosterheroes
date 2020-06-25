<?php

namespace App\Jobs;

use App\Domain\Actions\UpdateShopStock;
use App\Domain\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStockShopJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Shop
     */
    public $shop;

    /**
     * UpdateStockShopJob constructor.
     * @param Shop $shop
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * @param UpdateShopStock $updateShopStock
     * @throws \Exception
     */
    public function handle(UpdateShopStock $updateShopStock)
    {
        $updateShopStock->execute($this->shop);
    }
}
