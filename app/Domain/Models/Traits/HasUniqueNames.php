<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/17/19
 * Time: 8:31 PM
 */

namespace App\Domain\Models\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait UsesUniqueNames
 * @package App\Domain\Models\Traits
 *
 * @mixin Model
 */
trait HasUniqueNames
{
    public static function forName(string $name): ?self
    {
        /** @var self $model */
        $model = self::query()->where('name', '=', $name)->first();
        return $model;
    }
}