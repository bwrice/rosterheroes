<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:52 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;


use App\Domain\Collections\PlayerStatCollection;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NBAStatNameConverter;

class NBAStatAmountDTOBuilder implements StatAmountDTOBuilderInterface
{

    /**
     * @var NBAStatNameConverter
     */
    private $statNameConverter;

    public function __construct(NBAStatNameConverter $statNameConverter)
    {
        $this->statNameConverter = $statNameConverter;
    }

    public function getStatAmountDTOs(array $statsData): \Illuminate\Support\Collection
    {
        // TODO: Implement getStats() method.
    }
}