<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:39 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;

use App\Domain\Collections\StatTypeCollection;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\StatType;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NFLStatNameConverter;
use Illuminate\Support\Collection;

class NFLStatAmountDTOBuilder implements StatAmountDTOBuilder
{
    /**
     * @var NFLStatNameConverter
     */
    private $statNameConverter;

    public function __construct(NFLStatNameConverter $statNameConverter)
    {
        $this->statNameConverter = $statNameConverter;
    }

    public function getStatAmountDTOs(array $statsData): Collection
    {
        $stats = ! empty($statsData['passing']) ? $statsData['passing'] : [];
        $stats = ! empty($statsData['rushing']) ? array_merge($stats, $statsData['rushing']) : $stats;
        $stats = ! empty($statsData['receiving']) ? array_merge($stats, $statsData['receiving']) : $stats;
        $stats = ! empty($statsData['fumbles']) ? array_merge($stats, $statsData['fumbles']) : $stats;

        $convertedStats = collect($stats)->keyBy(function ($amount, $statName) {
            return $this->statNameConverter->convert($statName);
        })->reject(function ($amount, $statName) {
            return $statName === 'NONE' || ((int) round(abs($amount), 2)) === 0;
        });

        $statTypes = StatType::all();
        return $convertedStats->map(function ($amount, $convertedStatName) use ($statTypes) {
            $statType = $statTypes->firstWithName($convertedStatName);
            return new StatAmountDTO($statType, $amount);
        });
    }
}