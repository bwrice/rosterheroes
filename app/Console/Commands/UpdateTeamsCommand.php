<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use App\Jobs\UpdateTeamsJob;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class UpdateTeamsCommand extends IntegrationLeagueCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:update-teams {leagues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates teams for leagues input or live leagues from the stats integration API';


    protected function dispatchJobs(Collection $leagues)
    {
        $leagues->each(function (League $league) {
            UpdateTeamsJob::dispatch($league);
        });
    }

    public function getHandleMessage(): string
    {
        return "Update Teams command triggered";
    }
}
