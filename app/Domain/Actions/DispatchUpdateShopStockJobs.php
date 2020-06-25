<?php


namespace App\Domain\Actions;


use App\Domain\Models\Shop;
use App\Jobs\UpdateStockShopJob;
use Illuminate\Support\Facades\Date;

class DispatchUpdateShopStockJobs
{
    public function execute($randomDelay = true)
    {
        $shops = Shop::all();
        $shops->each(function (Shop $shop) use ($randomDelay) {

            $pendingDispatch = UpdateStockShopJob::dispatch($shop);

            if ($randomDelay) {
                $delay = now()->addMinutes(rand(15, 45));
                $pendingDispatch->delay($delay);
            }
        });
        return $shops->count();
    }
}
