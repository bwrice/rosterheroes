<?php

namespace App\Console\Commands;

use App\Domain\Actions\Testing\CreateTestSquadsAction;
use Illuminate\Console\Command;

class CreateTestSquadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh-testing:create-squads {amount=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auto-manageable Squads for testing';

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
     * @param CreateTestSquadsAction $domainAction
     */
    public function handle(CreateTestSquadsAction $domainAction)
    {
        $squads = $domainAction->execute((int) $this->argument('amount'));
        $this->info($squads->count() . " squads created");
    }
}
