<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 9:13 PM
 */

namespace App\Domain\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class GameQueryBuilder extends Builder
{

    /**
     * @param string $externalID
     * @return GameQueryBuilder
     */
    public function externalID(string $externalID)
    {
        return $this->where('external_id', '=', $externalID);
    }
}