<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorFactory;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorInterface;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetRange
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 */
class CombatPosition extends Model
{
    use HasUniqueNames;

    public const FRONT_LINE = 'Front Line';
    public const BACK_LINE = 'Back Line';
    public const HIGH_GROUND = 'High Ground';

    protected $guarded = [];

    /**
     * @return CombatPositionBehavior
     */
    public function getBehavior(): CombatPositionBehavior
    {
        /** @var CombatPositionBehaviorFactory $factory */
        $factory = app(CombatPositionBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }

    public function newCollection(array $models = [])
    {
        return new CombatPositionCollection($models);
    }

    public function getSVG($attacker = true)
    {
        return $this->getBehavior()->getSVG($attacker);
    }

    public static function frontLine(): self
    {
        return self::forName(self::FRONT_LINE);
    }

    public static function backLine(): self
    {
        return self::forName(self::BACK_LINE);
    }

    public static function highGround(): self
    {
        return self::forName(self::HIGH_GROUND);
    }
}
