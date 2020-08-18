<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use App\Jobs\UpdatePlayersJob;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UpdatePlayersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update-players {leagues?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates player for the given league or leagues that are live';

    public function handle()
    {
        $this->info('Update Players command triggered');
        foreach($this->arguments() as $key => $argument) {
            $this->info($key . ': ' . $argument );
        }
        $count = $this->dispatchJobs();
        $this->info($count . ' jobs dispatched');
    }

    protected function dispatchJobs()
    {
        $count = 0;

        $this->getLeagues()->each(function (League $league) use (&$count) {

            UpdatePlayersJob::dispatch($league)->onQueue('stats-integration')->delay($count * 5);
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
