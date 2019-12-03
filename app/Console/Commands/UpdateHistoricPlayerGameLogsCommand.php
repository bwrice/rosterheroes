<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Jobs\UpdateHistoricPlayerGameLogsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateHistoricPlayerGameLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:update-game-logs {leagues?} {yearsAgo=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Player Game Logs for live or given leagues';

    public function handle()
    {
        $this->info('Update Games command triggered');

        foreach($this->arguments() as $key => $argument) {
            $this->info($key . ': ' . $argument );
        }
        $count = $this->dispatchJobs();
        $this->info($count . " jobs dispatched");
    }

    protected function dispatchJobs()
    {
        $count = 0;
        // convert positive years-ago to negative
        $yearDelta = - (int) $this->argument('yearsAgo');
        // loop through leagues
        $this->getLeagues()->each(function (League $league) use (&$count, $yearDelta) {
            // loop through teams
            $league->teams->each(function(Team $team) use ($yearDelta, &$count) {
                UpdateHistoricPlayerGameLogsJob::dispatch($team, $yearDelta)->onQueue('my_sports_feeds')->delay($count * 15);
                $count++;
            });
        });

        return $count;
    }


    protected function getLeagues()
    {
        $leaguesArgument = $this->argument('leagues');

        if ($leaguesArgument) {

            $leagueAbbreviations = explode(',', $leaguesArgument);
            return League::abbreviation($leagueAbbreviations)->get();

        }
        return League::live();
    }
}
