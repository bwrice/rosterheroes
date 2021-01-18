<?php

namespace App\Console\Commands;

use App\Domain\Actions\NPC\DispatchAutoManageNPCJobs;
use Illuminate\Console\Command;

class ManageNPCs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npc:auto-manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Manage NPC squads';

    /**
     * @param DispatchAutoManageNPCJobs $dispatchAutoManageNPCJobs
     */
    public function handle(DispatchAutoManageNPCJobs $dispatchAutoManageNPCJobs)
    {
        $dispatchAutoManageNPCJobs->execute();
    }
}
