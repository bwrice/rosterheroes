<?php

namespace App\Domain\Models;

use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\QueryBuilders\AttackQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Attack
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 *
 * @property ItemCollection $items
 */
class Attack extends Model
{
    public const BASIC_BLADE_ATTACK_NAME = 'Cut';
    public const BASIC_BOW_ATTACK_NAME = 'Arrow';
    public const BASIC_MAGIC_ATTACK_NAME = 'Magic Bolt';

    public const START_SWORD_ATTACKS = [
        self::BASIC_BLADE_ATTACK_NAME
    ];
    public const STARTER_BOW_ATTACKS = [
        self::BASIC_BOW_ATTACK_NAME
    ];
    public const STARTER_STAFF_ATTACKS = [
        self::BASIC_MAGIC_ATTACK_NAME
    ];

    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemBases()
    {
        return $this->belongsToMany(ItemBase::class)->withTimestamps();
    }

    public function newCollection(array $models = [])
    {
        return new AttackCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new AttackQueryBuilder($query);
    }
}
