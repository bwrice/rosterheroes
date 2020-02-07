<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemClass
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class ItemClass extends Model
{
    const GENERIC = 'generic';
    const ENCHANTED = 'enchanted';
    const LEGENDARY = 'legendary';
    const MYTHICAL = 'mythical';

    protected $guarded = [];

    /**
     * @return ItemType
     */
    public static function generic()
    {
        /** @var ItemType $itemType */
        $itemType = static::query()->where('name', '=', self::GENERIC)->first();
        return $itemType;
    }

    /**
     * @return ItemType
     */
    public static function enchanted()
    {
        /** @var ItemType $itemType */
        $itemType = static::query()->where('name', '=', self::ENCHANTED)->first();
        return $itemType;
    }

    /**
     * @return ItemType
     */
    public static function legendary()
    {
        /** @var ItemType $itemType */
        $itemType = static::query()->where('name', '=', self::LEGENDARY)->first();
        return $itemType;
    }

    /**
     * @return ItemType
     */
    public static function mythical()
    {
        /** @var ItemType $itemType */
        $itemType = static::query()->where('name', '=', self::MYTHICAL)->first();
        return $itemType;
    }
}
