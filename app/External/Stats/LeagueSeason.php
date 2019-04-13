<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 9:45 PM
 */

namespace App\External\Stats;


use App\Domain\Models\League;

class LeagueSeason
{
    public function getCurrent()
    {
        $leagues = League::all();
    }
}