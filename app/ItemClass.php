<?php

namespace App;

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
}
