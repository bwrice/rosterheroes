<?php

namespace App\Jobs;

use App\Domain\Actions\UpdatePlayerSpiritEnergiesAction;
use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use MathPHP\Arithmetic;

class UpdatePlayerSpiritEnergiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Week
     */
    private $week;

    public function __construct(Week $week)
    {
        $this->week = $week;
    }

    /**
     * @param UpdatePlayerSpiritEnergiesAction $domainAction
     */
    public function handle(UpdatePlayerSpiritEnergiesAction $domainAction)
    {
        $domainAction->execute($this->week);
    }
}
