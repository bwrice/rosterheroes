<?php

namespace App\Jobs;

use App\Domain\Actions\OpenChest;
use App\Domain\Models\Chest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OpenChestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Chest $chest;

    public function __construct(Chest $chest)
    {
        $this->chest = $chest;
    }

    /**
     * @param OpenChest $openChest
     * @throws \Exception
     */
    public function handle(OpenChest $openChest)
    {
        $openChest->execute($this->chest);
    }
}
