<?php

namespace App\Console\Commands;

use App\Domain\Actions\UpdateHistoricGameLogsAction;
use App\Domain\Models\League;
use App\Jobs\UpdatePlayerGameLogsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateHistoricPlayerGameLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update-historic-game-logs {leagues?} {yearsAgo=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Player Game Logs for live or given leagues';

    public function handle(UpdateHistoricGameLogsAction $domainAction)
    {
        $this->info('Update Games command triggered');

        foreach($this->arguments() as $key => $argument) {
            $this->info($key . ': ' . $argument );
        }
        // convert positive years-ago to negative
        $yearDelta = - (int) $this->argument('yearsAgo');
        $leagues = $this->getLeagues();

        $count = $domainAction->execute($leagues, false, $yearDelta);
        Log::alert($count . " " . UpdatePlayerGameLogsJob::class . " jobs dispatched");
        $this->info($count . " jobs dispatched");
    }

    protected function getLeagues()
    {
        $leaguesArgument = $this->argument('leagues');

        if ($leaguesArgument) {

            $leagueAbbreviations = explode(',', $leaguesArgument);
            return League::abbreviation($leagueAbbreviations)->get();

        }
        return League::all();
    }
}
