<?php


namespace App\External\Stats\FakeStats;


use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Team;
use Illuminate\Support\Collection;

class CreateFakeStatAmountDTOsForPlayer
{
    protected $absoluteMinGameLogsCount = 5;

    public function execute(Player $player): Collection
    {
        $position = $player->positions->first();
        if (!$position) {
            return collect();
        }

        $gameLogsNeededCount = ceil(($position->getBehavior()->getGamesPerSeason()/10) + $this->absoluteMinGameLogsCount);
        $validGameLogs = $player->loadMissing('playerGameLogs.playerStats')->playerGameLogs->filter(function (PlayerGameLog $playerGameLog) {
            return $playerGameLog->playerStats->isNotEmpty();
        });
        if ($validGameLogs->isNotEmpty() && $validGameLogs->count() >= $gameLogsNeededCount) {
            return $this->getFromExistingGameLogs($validGameLogs);
        }

        return collect();
    }

    /**
     * @param int $absoluteMinGameLogsCount
     * @return CreateFakeStatAmountDTOsForPlayer
     */
    public function setAbsoluteMinGameLogsCount(int $absoluteMinGameLogsCount): CreateFakeStatAmountDTOsForPlayer
    {
        $this->absoluteMinGameLogsCount = $absoluteMinGameLogsCount;
        return $this;
    }

    /**
     * @param Collection $existingGameLogs
     * @return Collection
     */
    protected function getFromExistingGameLogs(Collection $existingGameLogs)
    {
        /** @var PlayerGameLog $gameLog */
        $gameLog = $existingGameLogs->random();
        return $gameLog->playerStats->map(function (PlayerStat $playerStat) {
            return new StatAmountDTO($playerStat->statType, $playerStat->amount);
        })->toBase();
    }
}
