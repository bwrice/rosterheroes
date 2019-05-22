<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/21/19
 * Time: 8:58 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\StatType;
use Illuminate\Database\Eloquent\Collection;

class StatTypeCollection extends Collection
{
    /**
     * @param string $name
     * @return StatType|null
     */
    public function firstWithName(string $name)
    {
        return $this->firstWhere('name', '=', $name);
    }
}