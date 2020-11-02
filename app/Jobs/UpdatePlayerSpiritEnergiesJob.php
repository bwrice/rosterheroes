<?php

namespace App\Jobs;

use App\Domain\Actions\UpdatePlayerSpiritEnergiesAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayerSpiritEnergiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param UpdatePlayerSpiritEnergiesAction $domainAction
     */
    public function handle(UpdatePlayerSpiritEnergiesAction $domainAction)
    {
        $domainAction->execute();
    }
}
