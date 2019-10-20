<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Minion
 * @package App\Domain\Models
 *
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $level
 * @property int $damage_rating
 * @property int $health_rating
 * @property int $protection_rating
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 */
class Minion extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }

    public function enemyType()
    {
        return $this->belongsTo(EnemyType::class);
    }

    protected function getEnemyTypeBehavior(): EnemyTypeBehavior
    {
        return $this->enemyType->getBehavior();
    }

    public function getStartingHealth(): int
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getHealthModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->health_rating * (1 + $enemyTypeBonus));
    }

    public function getProtection(): int
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->protection_rating * (1 + $enemyTypeBonus));
    }


}
