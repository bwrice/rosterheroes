<?php


namespace App\External\Stats\FakeStats;


use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
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

        return $this->buildRandomStatDTOsFromPosition($position);
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

    protected function buildRandomStatDTOsFromPosition(Position $position)
    {
        $statTypes = StatType::all();
        $statAmountDTOs = collect();

        $buildArrays = collect($this->getBuildDTOArrays($position));
        $buildArrays->each(function ($buildArray) use ($statTypes, $statAmountDTOs) {
            $dto = $this->buildStatAmountDTO($statTypes, $buildArray['stat_type_name'], $buildArray['min'], $buildArray['max'], $buildArray['lower_bound_weight']);
            if ($dto) {
                $statAmountDTOs->push($dto);
            }
        });
        return $statAmountDTOs;
    }

    protected function buildStatAmountDTO(Collection $statTypes, string $statTypeName, float $min, float $max, float $lowerBoundWeight = 1)
    {
        /*
         * Do some fancy math to make the amount closer to the min based on how high the lowerBoundWeight is
         */
        $minRange = (int) ceil(10000 * ($min**(1/$lowerBoundWeight)));
        // Add 5% to maxRange so there's at least a small chance of hitting the upper-bound number;
        $maxRange = (int) ceil(10000 * ($max**(1/$lowerBoundWeight)));
        $rand = rand($minRange, $maxRange);
        $amount = (int) floor(($rand/10000)**$lowerBoundWeight);

        if ($amount > 0) {
            /** @var StatType $statType */
            $statType = $statTypes->first(function (StatType $statType) use ($statTypeName) {
                return $statType->name === $statTypeName;
            });
            return new StatAmountDTO($statType, $amount);
        }
        return null;
    }

    public function getBuildDTOArrays(Position $position)
    {
        switch ($position->name) {
            case Position::QUARTERBACK:
                return [
                    [
                        'stat_type_name' => StatType::PASS_TD,
                        'min' => 0,
                        'max' => 3
                    ],
                    [
                        'stat_type_name' => StatType::PASS_YARD,
                        'min' => 85,
                        'max' => 350
                    ],
                    [
                        'stat_type_name' => StatType::INTERCEPTION,
                        'min' => 0,
                        'max' => 2
                    ]
                ];
            case Position::RUNNING_BACK:
                return [
                    [
                        'stat_type_name' => StatType::RUSH_TD,
                        'min' => 0,
                        'max' => 2
                    ],
                    [
                        'stat_type_name' => StatType::RUSH_YARD,
                        'min' => 25,
                        'max' => 110
                    ],
                    [
                        'stat_type_name' => StatType::REC_TD,
                        'min' => 0,
                        'max' => 1
                    ],
                    [
                        'stat_type_name' => StatType::REC_YARD,
                        'min' => 5,
                        'max' => 50
                    ],
                    [
                        'stat_type_name' => StatType::RECEPTION,
                        'min' => 1,
                        'max' => 5
                    ],
                    [
                        'stat_type_name' => StatType::FUMBLE_LOST,
                        'min' => 0,
                        'max' => 1
                    ]
                ];
            case Position::WIDE_RECEIVER:
                return [
                    [
                        'stat_type_name' => StatType::REC_TD,
                        'min' => 0,
                        'max' => 2
                    ],
                    [
                        'stat_type_name' => StatType::REC_YARD,
                        'min' => 50,
                        'max' => 180
                    ],
                    [
                        'stat_type_name' => StatType::RECEPTION,
                        'min' => 2,
                        'max' => 12
                    ],
                    [
                        'stat_type_name' => StatType::FUMBLE_LOST,
                        'min' => 0,
                        'max' => 1
                    ]
                ];
        }
        return [];
    }
}
