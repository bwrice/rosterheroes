<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:42 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;

interface StatAmountDTOBuilderInterface
{
    public function getStatAmountDTOs(array $statsData): \Illuminate\Support\Collection;
}