<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SquadRank
 * @package App
 *
 * @property int $id
 */
class SquadRank extends Model
{
    const CREW = 'crew';
    const TROUPE = 'troupe';
    const GANG = 'gang';
    const POSSE = 'posse';
    const CLAN = 'clan';
    const SQUADRON = 'squadron';
    const BATTALION = 'battalion';
    const LEGION = 'legion';

    protected $guarded = [];

    /**
     * @return SquadRank
     */
    public static function getStarting()
    {
        return self::where('name', '=', self::CREW)->first();
    }
}
