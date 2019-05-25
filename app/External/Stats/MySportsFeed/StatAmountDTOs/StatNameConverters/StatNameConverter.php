<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:32 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters;


interface StatNameConverter
{
    public function convert(string $msfStatName): string;
}