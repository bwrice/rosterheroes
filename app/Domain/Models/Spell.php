<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\Attributes\AttributeBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ResourceBehavior;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Collections\SpellCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Interfaces\SpellCaster;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Spell
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 *
 * @property MeasurableBoostCollection $measurableBoosts
 */
class Spell extends Model implements BoostsMeasurables
{
    const RELATION_MORPH_MAP_KEY = 'spells';

    /** @var SpellCaster|null */
    protected $spellCaster;

    protected $guarded = [];

    public static function getResourceRelations()
    {
        return [
            'measurableBoosts.measurableType',
            'measurableBoosts.booster'
        ];
    }

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }

    public function newCollection(array $models = [])
    {
        return new SpellCollection($models);
    }

    public function manaCost(): int
    {
        $boosterCount = $this->measurableBoosts->count();
        if (! $boosterCount) {
            return 0;
        }
        $boostLevelSum = $this->measurableBoosts->boostLevelSum();
        return ceil(50 * ($boostLevelSum ** .85) * (1/($boosterCount**.25)));
    }

    public function getMeasurableBoostMultiplier(MeasurableTypeBehavior $measurableTypeBehavior): float
    {
        $groupMultiplier = $this->getGroupBoostMultiplier($measurableTypeBehavior->getGroupName());
        $spellCasterModifier = $this->spellCaster ? $this->spellCaster->getSpellBoostMultiplier() : 1;
        return $groupMultiplier * $spellCasterModifier;
    }

    /**
     * @param SpellCaster|null $spellCaster
     * @return Spell
     */
    public function setSpellCaster(?SpellCaster $spellCaster): Spell
    {
        $this->spellCaster = $spellCaster;
        return $this;
    }

    /**
     * @return SpellCaster|null
     */
    public function getSpellCaster(): ?SpellCaster
    {
        return $this->spellCaster;
    }

    /**
     * @param string $groupName
     * @return int
     */
    protected function getGroupBoostMultiplier(string $groupName): int
    {
        switch ($groupName) {
            case ResourceBehavior::GROUP_NAME:
                return 8;
            case QualityBehavior::GROUP_NAME:
                return 4;
            case AttributeBehavior::GROUP_NAME:
                return 2;
        }
        throw new \InvalidArgumentException("Unknown group name: " . $groupName);
    }

    public function getMeasurableBoosts(): MeasurableBoostCollection
    {
        return $this->measurableBoosts;
    }
}
