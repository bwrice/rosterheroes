<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorFactory;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorInterface;
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

    public const FRONT_LINE = 'front-line';
    public const BACK_LINE = 'back-line';
    public const HIGH_GROUND = 'high-ground';

    protected $guarded = [];

    /**
     * @return CombatPositionBehaviorInterface
     */
    public function getBehavior(): CombatPositionBehaviorInterface
    {
        /** @var CombatPositionBehaviorFactory $factory */
        $factory = app(CombatPositionBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }

    public function getIcon($attacker = true)
    {
    }

    public function attackerIcon()
    {
        return $this->getBehavior()->attackerIcon();
    }

    public function targetIcon()
    {
        return $this->getBehavior()->targetIcon();
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
