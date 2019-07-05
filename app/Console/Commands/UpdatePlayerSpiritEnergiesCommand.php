<?php

namespace App\Console\Commands;

use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $week = Week::current();;
        $query = PlayerSpirit::query()->forWeek($week);
    }
}
