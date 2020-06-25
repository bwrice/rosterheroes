<?php

namespace App\Console\Commands;

use App\Domain\Actions\DispatchUpdateShopStockJobs;
use Illuminate\Console\Command;

class DispatchUpdateShopStockJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh:update-shop-stock {--now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch jobs to refill stocks of all shops';

    /**
     * @param DispatchUpdateShopStockJobs $dispatchUpdateShopStockJobs
     */
    public function handle(DispatchUpdateShopStockJobs $dispatchUpdateShopStockJobs)
    {
        $randomDelay = $this->option('now') ? false : true;
        $count = $dispatchUpdateShopStockJobs->execute($randomDelay);
        $this->info($count . " update-stock-jobs dispatched");
    }
}
