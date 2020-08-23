<?php

namespace App\Console\Commands;

use App\Domain\Actions\NPC\AutoManageNPCCampaign;
use App\Domain\Models\Squad;
use App\Jobs\AutoManageNPCHeroesJob;
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param AutoManageNPCCampaign $autoManageNPCCampaign
     */
    public function handle(AutoManageNPCCampaign $autoManageNPCCampaign)
    {
        $squadNPCs = Squad::query()->npc()->get();
        $squadNPCs->each(function (Squad $npc) use ($autoManageNPCCampaign) {
            $autoManageNPCCampaign->execute($npc);
            AutoManageNPCHeroesJob::dispatch($npc);
        });
        $this->info("Auto management started for " . $squadNPCs->count() . " NPCs");
    }
}
