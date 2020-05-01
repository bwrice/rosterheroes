<?php


namespace App\External\Stats\FakeStats;


use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
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
        // Add 2% to maxRange so there's at least a small chance of hitting the upper-bound number;
        $maxRange = (int) ceil(10200 * ($max**(1/$lowerBoundWeight)));
        $rand = rand($minRange, $maxRange);
        $amount = floor(($rand/10000)**$lowerBoundWeight);

        // allow float $max values for better results but make sure we don't go over the floor() of $max
        $amount = (int) min($amount, floor($max));

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
                        'min' => .75,
                        'max' => 4,
                        'lower_bound_weight' => 10
                    ],
                    [
                        'stat_type_name' => StatType::PASS_YARD,
                        'min' => 85,
                        'max' => 350,
                        'lower_bound_weight' => 5
                    ],
                    [
                        'stat_type_name' => StatType::INTERCEPTION,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 5
                    ]
                ];
            case Position::RUNNING_BACK:
                return [
                    [
                        'stat_type_name' => StatType::RUSH_TD,
                        'min' => .1,
                        'max' => 2,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::RUSH_YARD,
                        'min' => 10,
                        'max' => 120,
                        'lower_bound_weight' => 20
                    ],
                    [
                        'stat_type_name' => StatType::REC_TD,
                        'min' => 0,
                        'max' => 1.5,
                        'lower_bound_weight' => 10
                    ],
                    [
                        'stat_type_name' => StatType::REC_YARD,
                        'min' => 4,
                        'max' => 50,
                        'lower_bound_weight' => 15
                    ],
                    [
                        'stat_type_name' => StatType::RECEPTION,
                        'min' => 1,
                        'max' => 5,
                        'lower_bound_weight' => 15
                    ],
                    [
                        'stat_type_name' => StatType::FUMBLE_LOST,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 10
                    ]
                ];
            case Position::WIDE_RECEIVER:
                return [
                    [
                        'stat_type_name' => StatType::REC_TD,
                        'min' => .1,
                        'max' => 2.5,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::RECEPTION,
                        'min' => 2,
                        'max' => 12,
                        'lower_bound_weight' => 8
                    ],
                    [
                        'stat_type_name' => StatType::REC_YARD,
                        'min' => 10,
                        'max' => 125,
                        'lower_bound_weight' => 15
                    ]
                ];
            case Position::TIGHT_END:
                return [
                    [
                        'stat_type_name' => StatType::REC_TD,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::RECEPTION,
                        'min' => 1,
                        'max' => 10,
                        'lower_bound_weight' => 5
                    ],
                    [
                        'stat_type_name' => StatType::REC_YARD,
                        'min' => 8,
                        'max' => 115,
                        'lower_bound_weight' => 25
                    ]
                ];
            case Position::OUTFIELD:
                $bonusMod = 3.2;
            case Position::FIRST_BASE:
            case Position::SECOND_BASE:
            case Position::THIRD_BASE:
            case Position::SHORTSTOP:
                $bonusMod = $bonusMod ?? 2;
            case Position::CATCHER:
                $bonusMod = $bonusMod ?? 1;
                return [
                    [
                        'stat_type_name' => StatType::HIT,
                        'min' => 0.12 * $bonusMod,
                        'max' => 4,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::DOUBLE,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 20
                    ],
                    [
                        'stat_type_name' => StatType::TRIPLE,
                        'min' => 0,
                        'max' => 1,
                        'lower_bound_weight' => 20
                    ],
                    [
                        'stat_type_name' => StatType::HOME_RUN,
                        'min' => 0.1 * $bonusMod,
                        'max' => 2,
                        'lower_bound_weight' => 20
                    ],
                    [
                        'stat_type_name' => StatType::RUN_BATTED_IN,
                        'min' => 0.12 * $bonusMod,
                        'max' => 4,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::BASE_ON_BALLS,
                        'min' => 1,
                        'max' => 3,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::STOLEN_BASE,
                        'min' => 0.08 * $bonusMod,
                        'max' => 2,
                        'lower_bound_weight' => 25
                    ],
                ];
            case Position::PITCHER:
                return [
                    [
                        'stat_type_name' => StatType::INNING_PITCHED,
                        'min' => 1,
                        'max' => 7,
                        'lower_bound_weight' => 5
                    ],
                    [
                        'stat_type_name' => StatType::EARNED_RUN_ALLOWED,
                        'min' => 0.1,
                        'max' => 6,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::STRIKEOUT,
                        'min' => 1,
                        'max' => 8,
                        'lower_bound_weight' => 4
                    ],
                    [
                        'stat_type_name' => StatType::HIT_AGAINST,
                        'min' => .5,
                        'max' => 7,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::BASE_ON_BALLS_AGAINST,
                        'min' => 0.4,
                        'max' => 6,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::PITCHING_WIN,
                        'min' => 0.5, // Needs to be close to one since we use floor() when rounding
                        'max' => 1.25,
                        'lower_bound_weight' => 1
                    ],
                ];
            case Position::POINT_GUARD:
            case Position::SMALL_FORWARD:
            case Position::SHOOTING_GUARD:
                $bonusMod = 1.5;
            case Position::POWER_FORWARD:
            case Position::BASKETBALL_CENTER:
                $bonusMod = $bonusMod ?? 1;
                return [
                    [
                        'stat_type_name' => StatType::POINT_MADE,
                        'min' => 5 * $bonusMod,
                        'max' => 35,
                        'lower_bound_weight' => 3
                    ],
                    [
                        'stat_type_name' => StatType::THREE_POINTER,
                        'min' => 0.4 * $bonusMod,
                        'max' => 5,
                        'lower_bound_weight' => 6
                    ],
                    [
                        'stat_type_name' => StatType::REBOUND,
                        'min' => 1 * $bonusMod,
                        'max' => 12,
                        'lower_bound_weight' => 3
                    ],
                    [
                        'stat_type_name' => StatType::BASKETBALL_ASSIST,
                        'min' => 1 * $bonusMod,
                        'max' => 12,
                        'lower_bound_weight' => 3
                    ],
                    [
                        'stat_type_name' => StatType::STEAL,
                        'min' => 0,
                        'max' => 3,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::BASKETBALL_BLOCK,
                        'min' => 0,
                        'max' => 3,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::TURNOVER,
                        'min' => 0,
                        'max' => 6,
                        'lower_bound_weight' => 25
                    ],
                ];
            case Position::HOCKEY_CENTER:
            case Position::LEFT_WING:
            case Position::RIGHT_WING:
                return [
                    [
                        'stat_type_name' => StatType::GOAL,
                        'min' => 0,
                        'max' => 2.4,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::HOCKEY_ASSIST,
                        'min' => 0,
                        'max' => 3,
                        'lower_bound_weight' => 15
                    ],
                    [
                        'stat_type_name' => StatType::SHOT_ON_GOAL,
                        'min' => 1,
                        'max' => 4,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::HOCKEY_BLOCKED_SHOT,
                        'min' => 0,
                        'max' => 4,
                        'lower_bound_weight' => 15
                    ]
                ];
            case Position::DEFENSEMAN:
                return [
                    [
                        'stat_type_name' => StatType::GOAL,
                        'min' => 0,
                        'max' => 1.5,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::HOCKEY_ASSIST,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 15
                    ],
                    [
                        'stat_type_name' => StatType::SHOT_ON_GOAL,
                        'min' => 0,
                        'max' => 2,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::HOCKEY_BLOCKED_SHOT,
                        'min' => 1,
                        'max' => 8,
                        'lower_bound_weight' => 25
                    ]
                ];
            case Position::GOALIE:
                return [
                    [
                        'stat_type_name' => StatType::GOALIE_WIN,
                        'min' => .3,
                        'max' => 1.4,
                        'lower_bound_weight' => 1
                    ],
                    [
                        'stat_type_name' => StatType::GOALIE_SAVE,
                        'min' => 3,
                        'max' => 25,
                        'lower_bound_weight' => 25
                    ],
                    [
                        'stat_type_name' => StatType::GOAL_AGAINST,
                        'min' => 0,
                        'max' => 4,
                        'lower_bound_weight' => 25
                    ]
                ];
        }
        return [];
    }
}
