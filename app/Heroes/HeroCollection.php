<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/13/19
 * Time: 12:35 PM
 */

namespace App\Heroes;


use App\Hero;
use Illuminate\Database\Eloquent\Collection;

class HeroCollection extends Collection
{
    /**
     * @return int
     */
    public function totalSalary()
    {
        return $this->sum(function(Hero $hero) {
            return $hero->salary ?: 0;
        });
    }
}