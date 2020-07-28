<?php

namespace App\Jobs;

use App\Domain\Actions\UpdateSingleGame;
use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\StatsIntegrationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSingleGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var StatsIntegrationType
     */
    public $statsIntegrationType;
    /**
     * @var GameDTO
     */
    public $gameDTO;

    public function __construct(StatsIntegrationType $statsIntegrationType, GameDTO $gameDTO)
    {
        $this->statsIntegrationType = $statsIntegrationType;
        $this->gameDTO = $gameDTO;
    }

    /**
     * @param UpdateSingleGame $domainAction
     */
    public function handle(UpdateSingleGame $domainAction)
    {
        $domainAction->execute($this->statsIntegrationType, $this->gameDTO);
    }
}
