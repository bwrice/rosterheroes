<?php

namespace App\Console\Commands;

use App\Domain\Actions\BuildInitialWeekAction;
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
        $buildInitialWeekAction->execute();
    }
}
