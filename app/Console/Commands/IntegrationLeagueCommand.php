<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

abstract class IntegrationLeagueCommand extends Command
{
    abstract public function getHandleMessage(): string;

    abstract protected function dispatchJobs(Collection $leagues);

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info($this->getHandleMessage());

        $leaguesArgument = $this->argument('leagues');
        if ($leaguesArgument) {

            $leagueAbbreviations = explode(',', $leaguesArgument);
            $this->dispatchJobs(League::abbreviation($leagueAbbreviations)->get());

        } else {

            $leagues = League::all()->filter(function (League $league) {
                return $league->isLive();
            });
            $this->dispatchJobs($leagues);
        }
    }
}
