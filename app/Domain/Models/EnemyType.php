<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehaviorFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class EnemyType
 * @package App\Domain\Models
 *
 * @property string $name
 */
class EnemyType extends Model
{
    protected $guarded = [];

    public const GIANT = 'giant';
    public const GARGOYLE = 'gargoyle';
    public const GOLEM = 'golem';
    public const IMP = 'imp';
    public const TROLL = 'troll';
    public const UNDEAD = 'undead';
    public const VAMPIRE = 'vampire';
    public const WEREWOLF = 'werewolf';
    public const WITCH = 'witch';

    public function getBehavior(): EnemyTypeBehavior
    {
        /** @var EnemyTypeBehaviorFactory $factory */
        $factory = app(EnemyTypeBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }

    public function getPluralName($count = 2)
    {
        $name = $this->name;
        switch ($name) {
            case self::UNDEAD:
                return self::UNDEAD;
        }
        return ucwords(Str::plural($this->name, $count));
    }
}
