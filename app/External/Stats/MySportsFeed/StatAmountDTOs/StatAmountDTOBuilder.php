<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:30 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;


use App\Domain\Collections\StatTypeCollection;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\StatType;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\StatNameConverter;
use Illuminate\Support\Collection;

class StatAmountDTOBuilder
{
    /**
     * @var StatNameConverter
     */
    private $statNameConverter;
    /**
     * @var array
     */
    private $subArrayKeys;

    public function __construct(StatNameConverter $statNameConverter, array $subArrayKeys)
    {
        $this->statNameConverter = $statNameConverter;
        $this->subArrayKeys = $subArrayKeys;
    }

    public function getStatAmountDTOs(StatTypeCollection $statTypes, array $statsData): Collection
    {
        $stats = [];
        foreach($this->subArrayKeys as $subArrayKey) {
            $stats = ! empty($statsData[$subArrayKey]) ? array_merge($stats, $statsData[$subArrayKey]) : $stats;
        }

        $convertedStats = collect($stats)->keyBy(function ($amount, $statName) {
            return $this->statNameConverter->convert($statName);
        })->reject(function ($amount, $statName) {
            return $statName === 'NONE' || ((int) round(abs($amount), 2)) === 0;
        });

        return $convertedStats->map(function ($amount, $convertedStatName) use ($statTypes) {
            $statType = $statTypes->firstWithName($convertedStatName);
            return new StatAmountDTO($statType, $amount);
        });
    }
}