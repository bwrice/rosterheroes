<?php

namespace App\Console\Commands;

use App\Domain\Actions\UpdatePlayerSpiritEnergiesAction;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class UpdatePlayerSpiritEnergiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:update-player-spirit-energies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates and updates the energies for player spirits for a given week';

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
     * @param UpdatePlayerSpiritEnergiesAction $domainAction
     */
    public function handle(UpdatePlayerSpiritEnergiesAction $domainAction)
    {
        $before = Date::now();

        $domainAction->execute();

        $diffForHumans = Date::now()->longAbsoluteDiffForHumans($before);
        Log::alert("Updated spirit energies in " . $diffForHumans);
    }
}
