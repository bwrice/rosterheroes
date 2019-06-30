<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 9:48 PM
 */

namespace App\Domain\Interfaces;


use Illuminate\Database\Eloquent\Builder;

interface EssenceCostQueryable
{
    public function minEssenceCost(int $amount): Builder;

    public function maxEssenceCost(int $amount): Builder;
}