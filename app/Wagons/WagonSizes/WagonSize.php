<?php

namespace App\Wagons\WagonSizes;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WagonSize
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class WagonSize extends Model
{
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    protected $guarded = [];

    /**
     * @return static
     */
    public static function getStarting()
    {
        return self::where('name', '=', self::SMALL)->first();
    }

    /**
     * @return WagonSizeBehavior
     */
    public function getBehavior()
    {
        switch( $this->name ) {
            case self::SMALL:
            default:
                return new SmallBehavior();
            case self::MEDIUM:
                return new MediumBehavior();
            case self::LARGE:
                return new LargeBehavior();
        }
    }
}
