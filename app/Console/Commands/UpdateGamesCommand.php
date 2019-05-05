<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/30/19
 * Time: 10:43 PM
 */

namespace App\Console\Commands;


use App\Domain\Models\League;
use App\Jobs\UpdatePlayersJob;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

class UpdateGamesCommand extends IntegrationLeagueCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:update-games {leagues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates games for the given league or leagues that are live';


    protected function dispatchJobs(Collection $leagues)
    {
        $delayMinutes = 0;

        $leagues->each(function (League $league) use (&$delayMinutes) {

            $delay = CarbonImmutable::now()->addMinutes($delayMinutes);
            UpdatePlayersJob::dispatch($league)->delay($delay->toDateTime());
            $delayMinutes += 5;
        });
    }

    public function getHandleMessage(): string
    {
        return "Update Games command triggered";
    }
}