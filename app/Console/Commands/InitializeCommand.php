<?php

namespace App\Console\Commands;

use App\Domain\Actions\BuildInitialWeekAction;
use App\Facades\WeekService;
use Illuminate\Console\Command;

class InitializeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Roster Heroes';

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
     * @param BuildInitialWeekAction $buildInitialWeekAction
     */
    public function handle(BuildInitialWeekAction $buildInitialWeekAction)
    {
        $week = $buildInitialWeekAction->execute();
        $this->info("Initial Week Created!");
        $adventuringLocksDescription = $week->adventuring_locks_at->setTimezone('America/New_York')->shortRelativeDiffForHumans();
        $this->info("Adventuring Locks: " . $adventuringLocksDescription);
        $finalizingStartsAtDescription = WeekService::finalizingStartsAt($week->adventuring_locks_at)->setTimezone('America/New_York')->shortRelativeDiffForHumans();
        $this->info("Adventuring Locks: " . $finalizingStartsAtDescription);
    }
}
