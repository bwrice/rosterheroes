<?php

namespace App\Console\Commands;

use App\Domain\Actions\DispatchJobToFinalizeWeek;
use Illuminate\Console\Command;

class DispatchJobToFinalizeWeekCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh:dispatch-finalize-week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param DispatchJobToFinalizeWeek $dispatchJobToFinalizeWeek
     */
    public function handle(DispatchJobToFinalizeWeek $dispatchJobToFinalizeWeek)
    {
        $dispatchJobToFinalizeWeek->execute();
        $this->info("Finalize Week Job dispatched");
    }
}
