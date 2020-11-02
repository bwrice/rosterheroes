<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\DamageTypes\AreaOfEffectBehavior;
use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\DamageTypes\DispersedBehavior;
use App\Domain\Behaviors\DamageTypes\FixedTargetBehavior;
use App\Domain\Models\DamageType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DamageTypeService
 * @package App\Services\Models\Reference
 *
 * @method DamageTypeBehavior getBehavior($identifier)
 */
class DamageTypeService extends ReferenceService
{
    public function __construct()
    {
        $this->behaviors[DamageType::FIXED_TARGET] = app(FixedTargetBehavior::class);
        $this->behaviors[DamageType::DISPERSED] = app(DispersedBehavior::class);
        $this->behaviors[DamageType::AREA_OF_EFFECT] = app(AreaOfEffectBehavior::class);
    }

    protected function all(): Collection
    {
        return DamageType::all();
    }

    public function maxTargetsCount($identifier, int $tier, int $targetsCount = null)
    {
        return $this->getBehavior($identifier)->getMaxTargetCount($tier, $targetsCount);
    }

    public function damagePerTarget($identifier, int $totalDamage, int $targetsCount)
    {
        return $this->getBehavior($identifier)->getDamagePerTarget($totalDamage, $targetsCount);
    }
}
