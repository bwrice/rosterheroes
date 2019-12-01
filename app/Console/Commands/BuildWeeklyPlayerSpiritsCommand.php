<?php

namespace App\Console\Commands;

use App\Domain\Actions\BuildWeeklyPlayerSpiritsAction;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidWeekException;
use App\Jobs\CreatePlayerSpiritJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class BuildPlayerSpirits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:build-player-spirits {week?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build player spirits';

    public function handle(BuildWeeklyPlayerSpiritsAction $action)
    {
        $week = $this->getWeek();
        $action->execute($week);
    }

    /**
     * @return Week
     */
    protected function getWeek()
    {
        $weekID = $this->argument('week');
        if ($weekID) {
            /** @var Week $week */
            $week = Week::query()->findOrFail($weekID);
            return $week;
        }
        return Week::current();
    }
}
