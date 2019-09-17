<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MaterialTypes\BoneBehavior;
use App\Domain\Behaviors\MaterialTypes\ClothBehavior;
use App\Domain\Behaviors\MaterialTypes\GemstoneBehavior;
use App\Domain\Behaviors\MaterialTypes\HideBehavior;
use App\Domain\Behaviors\MaterialTypes\MaterialTypeBehaviorInterface;
use App\Domain\Behaviors\MaterialTypes\MetalBehavior;
use App\Domain\Behaviors\MaterialTypes\PreciousMetalBehavior;
use App\Domain\Behaviors\MaterialTypes\PsionicBehavior;
use App\Domain\Behaviors\MaterialTypes\WoodBehavior;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class MaterialType extends Model
{
    const HIDE = 'hide';
    const METAL = 'metal';
    const CLOTH = 'cloth';
    const WOOD = 'wood';
    const GEMSTONE = 'gemstone';
    const BONE = 'bone';
    const PRECIOUS_METAL = 'precious-metal';
    const PSIONIC = 'psionic';

    use HasUniqueNames;

    protected $guarded = [];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function getBehavior(): MaterialTypeBehaviorInterface
    {
        switch ($this->name) {
            case self::HIDE:
                return app(HideBehavior::class);
            case self::METAL:
                return app(MetalBehavior::class);
            case self::CLOTH:
                return app(ClothBehavior::class);
            case self::WOOD:
                return app(WoodBehavior::class);
            case self::GEMSTONE:
                return app(GemstoneBehavior::class);
            case self::BONE:
                return app(BoneBehavior::class);
            case self::PRECIOUS_METAL:
                return app(PreciousMetalBehavior::class);
            case self::PSIONIC:
                return app(PsionicBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, MaterialTypeBehaviorInterface::class);
    }

    public function getWeightModifier()
    {
        return $this->getBehavior()->getWeightModifier();
    }

    public function getProtectionModifier()
    {
        return $this->getBehavior()->getProtectionModifier();
    }
}
