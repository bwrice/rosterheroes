<?php

namespace App\Console\Commands;

use App\Domain\Actions\Testing\CreateTestSquadAction;
use App\Domain\Models\Squad;
use App\Jobs\CreateTestSquadJob;
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

    public function handle()
    {
        $offset = Squad::query()->count();
        $amount = (int) $this->argument('amount');
        for ($i = 1; $i <= $amount; $i++) {
            CreateTestSquadJob::dispatch($offset + $i);
        }
        $this->info($amount . " create test-squad jobs dispatched");
    }
}
