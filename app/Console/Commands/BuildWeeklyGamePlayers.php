<?php

namespace App\Console\Commands;

use App\Domain\Models\Week;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class BuildWeeklyGamePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Determine week to create weekly game players
        //find games within correct range
        //log if no games found
        //loop through games
        //find players of both teams
        //find highest value position
        //create weekly game player
    }
}
