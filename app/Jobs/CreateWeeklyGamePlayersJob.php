<?php

namespace App\Jobs;

use App\Domain\Models\League;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateWeeklyGamePlayersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Week
     */
    private $week;
    /**
     * @var League
     */
    private $league;

    public function __construct(Week $week, League $league)
    {
        $this->week = $week;
        $this->league = $league;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
