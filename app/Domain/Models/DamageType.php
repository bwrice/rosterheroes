<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorFactory;
use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DamageType
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 */
class DamageType extends Model
{
    use HasUniqueNames;

    public const FIXED_TARGET = 'Fixed Target';
    public const AREA_OF_EFFECT = 'Area of Effect';
    public const DISPERSED = 'Dispersed';

    protected $guarded = [];

    /**
     * @return DamageTypeBehavior
     */
    public function getBehavior(): DamageTypeBehavior
    {
        /** @var DamageTypeBehaviorFactory $factory */
        $factory = app(DamageTypeBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }
}
