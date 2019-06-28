<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/24/19
 * Time: 11:09 PM
 */

namespace App\Domain\Interfaces;


use Illuminate\Database\Eloquent\Builder;

interface PositionQueryable
{
    public function withPositions(array $positions): Builder;
}