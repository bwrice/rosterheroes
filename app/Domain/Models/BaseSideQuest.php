<?php

namespace App\Domain\Models;

use App\BaseMinion;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class BaseSideQuest
 * @package App\Domain\Models
 *
 * @property string $uuid
 * @property string|null $name
 */
abstract class BaseSideQuest extends Model
{
    protected $guarded = [];

    use HasUuid;

    public function buildName()
    {
        if ($this->name) {
            return $this->name;
        }

        $minions = $this->getBaseMinions();
        if ($minions->count() === 1) {
            /** @var BaseMinion $firstMinion */
            $firstMinion = $minions->first();
            return Str::plural($firstMinion->name, $firstMinion->pivot->count);
        }

        $enemyTypes = $minions->map(function (BaseMinion $minion) {
            return $minion->enemyType;
        })->unique('id');

        if ($enemyTypes->count() === 1) {
            /** @var EnemyType $enemyType */
            $enemyType = $enemyTypes->first();
            return ucwords($enemyType->getPluralName()) . ' (Mixed)';
        }

        return 'Minions (Mixed)';
    }

    abstract protected function getBaseMinions(): \Illuminate\Support\Collection;
}
