<?php

namespace App\Console\Commands;

use App\Domain\Actions\Testing\InitiateTestSquadManagementAction;
use Illuminate\Console\Command;

class ManageTestSquadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh-testing:manage-squads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-manage testing squads';

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
     * @param InitiateTestSquadManagementAction $domainAction
     */
    public function handle(InitiateTestSquadManagementAction $domainAction)
    {
        $count = $domainAction->execute();
        $this->info("Auto-management for " . $count . " squads executed");
    }
}
