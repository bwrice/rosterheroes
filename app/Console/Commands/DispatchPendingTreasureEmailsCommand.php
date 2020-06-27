<?php

namespace App\Console\Commands;

use App\Domain\Actions\DispatchPendingTreasureEmails;
use Illuminate\Console\Command;

class DispatchPendingTreasureEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:pending-treasure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch emails to users with unopened treasure chests';

    /**
     * @param DispatchPendingTreasureEmails $dispatchPendingTreasureEmails
     */
    public function handle(DispatchPendingTreasureEmails $dispatchPendingTreasureEmails)
    {
        $count = $dispatchPendingTreasureEmails->execute();
        $this->info($count . " emails dispatched");
    }
}
