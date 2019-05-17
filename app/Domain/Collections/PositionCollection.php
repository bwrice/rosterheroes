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
        return $this->map(function (Position $position) {
            return $position->name;
        });
    }

    /**
     * @return Position|null
     */
    public function withHighestDefaultSalary()
    {
        return $this->sortDefaultSalary(true)->first();
    }

    public function sortDefaultSalary($descending = false, $options = SORT_REGULAR)
    {
        return $this->sortBy(function (Position $position) {
            return $position->getBehavior()->getDefaultSalary();
        }, $options, $descending);
    }
}