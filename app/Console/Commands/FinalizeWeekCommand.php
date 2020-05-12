<?php

namespace App\Console\Commands;

use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use Illuminate\Console\Command;

class FinalizeWeekCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:finalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force week finalizing';

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
     * Execute the console command.
     */
    public function handle()
    {
        if (! CurrentWeek::finalizing()) {
            $this->info("Week not ready to be finalized");
        } else {
            $finalize = strtolower($this->ask("Finalizing week could potentially conflict with a pending finalize week job. Continue? (y/n)"));
            if ($finalize === 'y' || $finalize === 'yes') {

                $step = 1;
                FinalizeWeekJob::dispatch($step);
                $this->info("Dispatching FinalizeWeekJob with step: " . $step);

            } else {
                $this->info("Cancelled");
            }
        }
    }
}
