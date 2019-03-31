<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/19/19
 * Time: 3:57 PM
 */

namespace App\Domain\Collections;

use App\Domain\Models\Position;
use Illuminate\Database\Eloquent\Collection;

class PositionCollection extends Collection
{
    public function names()
    {
        return $this->map(function (\App\Domain\Models\Position $position) {
            return $position->name;
        });
    }
}