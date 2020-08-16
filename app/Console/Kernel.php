<?php

namespace App\Console;

use App\Console\Commands\BuildWeeklyPlayerSpiritsCommand;
use App\Console\Commands\DispatchUpdateShopStockJobsCommand;
use App\Console\Commands\UpdateGamesCommand;
use App\Console\Commands\UpdateHistoricPlayerGameLogsCommand;
use App\Console\Commands\UpdatePlayersCommand;
use App\Console\Commands\UpdatePlayerSpiritEnergiesCommand;
use App\Console\Commands\UpdateTeamsCommand;
use App\Jobs\InitiateTestSquadManagementJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command(UpdateTeamsCommand::class)->cron('0 */6 * * *');
        $schedule->command(UpdatePlayersCommand::class)->cron('10 */6 * * *');
//        $schedule->command(UpdateGamesCommand::class)->cron('30 */6 * * *');

        $schedule->command(UpdateHistoricPlayerGameLogsCommand::class)->cron('50 */6 * * *');

        $schedule->command(BuildWeeklyPlayerSpiritsCommand::class)->cron('0 */12 * * *');
        $schedule->command(UpdatePlayerSpiritEnergiesCommand::class)->cron('0 */2 * * *');

        $schedule->command(DispatchUpdateShopStockJobsCommand::class)->cron('0 */3 * * *');

        $schedule->job(new InitiateTestSquadManagementJob())->cron('0 12 * * 4')->when(function () {
            return app()->environment('local', 'staging');
        });

        /*
         * Spatie back-up package. Backs up files and DB daily.
         */
        $schedule->command('backup:run')->when(function() {
            return ! app()->environment('local');
        })->cron('0 5 * * *');

        $schedule->command('backup:clean')->when(function() {
            return ! app()->environment('local');
        })->cron('30 5 * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
