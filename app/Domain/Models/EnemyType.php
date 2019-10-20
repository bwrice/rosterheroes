<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehaviorFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EnemyType
 * @package App\Domain\Models
 *
 * @property string $name
 */
class EnemyType extends Model
{
    protected $guarded = [];

    public const SKELETON = 'Skeleton';

    public function getBehavior(): EnemyTypeBehavior
    {
        /** @var EnemyTypeBehaviorFactory $factory */
        $factory = app(EnemyTypeBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }
}
