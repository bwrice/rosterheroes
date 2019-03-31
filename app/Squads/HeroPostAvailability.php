<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 2/3/19
 * Time: 6:59 PM
 */

namespace App\Squads;


use App\Domain\Models\Squad;

class HeroPostAvailability
{
    /**
     * @param Squad $squad
     * @return \App\Domain\Collections\HeroPostCollection
     */
    public function get(Squad $squad)
    {
        return $squad->heroPosts->postFilled(false);
    }
}