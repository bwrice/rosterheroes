<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use App\Jobs\UpdateTeamsJob;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class UpdateTeamsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update-teams {leagues?} {yearsAgo=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates teams for leagues input or live leagues from the stats integration API';

    public function handle()
    {
        $this->info('Update Teams command triggered');
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
        $this->getLeagues()->each(function (League $league) use (&$count, $yearDelta) {
            UpdateTeamsJob::dispatch($league, $yearDelta);
            $count++;
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
        return League::all();
    }
}
