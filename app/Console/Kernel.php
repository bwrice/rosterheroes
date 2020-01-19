<?php

namespace App\Console;

use App\Console\Commands\BuildWeeklyPlayerSpiritsCommand;
use App\Console\Commands\UpdateGamesCommand;
use App\Console\Commands\UpdatePlayersCommand;
use App\Console\Commands\UpdatePlayerSpiritEnergiesCommand;
use App\Console\Commands\UpdateTeamsCommand;
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
        $schedule->command(UpdateTeamsCommand::class)->cron('0 */6 * * *');
        $schedule->command(UpdatePlayersCommand::class)->cron('10 */6 * * *');
        $schedule->command(UpdateGamesCommand::class)->cron('30 */6 * * *');

        $schedule->command(BuildWeeklyPlayerSpiritsCommand::class)->cron('0 */12 * * *');
        $schedule->command(UpdatePlayerSpiritEnergiesCommand::class)->cron('0 */2 * * *');
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
