<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/19/19
 * Time: 3:57 PM
 */

namespace App\Positions;

use App\Positions\Position;
use Illuminate\Database\Eloquent\Collection;

class PositionCollection extends Collection
{
    public function names()
    {
        return $this->map(function (Position $position) {
            return $position->name;
        });
    }
}