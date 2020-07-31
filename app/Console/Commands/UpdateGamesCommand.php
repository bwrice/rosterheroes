<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/30/19
 * Time: 10:43 PM
 */

namespace App\Console\Commands;


use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Jobs\UpdateGamesJob;
use App\Jobs\UpdatePlayersJob;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class UpdateGamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update-games {leagues?} {yearsAgo=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates games for the given league or leagues that are live';

    public function handle()
    {
        $this->info('Update Games command triggered');

        foreach($this->arguments() as $key => $argument) {
            $this->info($key . ': ' . $argument );
        }
        $count = $this->dispatchJobs();
        $this->info($count . " jobs dispatched");
    }

    /**
     * @return int
     */
    protected function dispatchJobs()
    {
        $count = 0;
        // convert positive years-ago to negative
        $yearDelta = - (int) $this->argument('yearsAgo');
        $this->getLeagues()->each(function (League $league) use (&$count, $yearDelta) {

            UpdateGamesJob::dispatch($league, $yearDelta)->onQueue('stats-integration')->delay($count * 5);
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
