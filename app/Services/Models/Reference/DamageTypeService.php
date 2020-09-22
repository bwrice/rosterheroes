<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\DamageTypes\AreaOfEffectBehavior;
use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\DamageTypes\DispersedBehavior;
use App\Domain\Behaviors\DamageTypes\FixedTargetBehavior;
use App\Domain\Models\DamageType;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Collection;

class DamageTypeService
{
    protected ?Collection $damageTypes = null;
    protected array $behaviors = [];

    public function __construct()
    {
        $this->behaviors[DamageType::FIXED_TARGET] = app(FixedTargetBehavior::class);
        $this->behaviors[DamageType::DISPERSED] = app(DispersedBehavior::class);
        $this->behaviors[DamageType::AREA_OF_EFFECT] = app(AreaOfEffectBehavior::class);
    }

    /**
     * @param $identifier
     * @return DamageTypeBehavior
     */
    public function getBehavior($identifier)
    {
        if (is_numeric($identifier)) {
            return $this->getBehaviorByID((int) $identifier);
        }
        return $this->getBehaviorByName($identifier);
    }

    /**
     * @param string $name
     * @return DamageTypeBehavior
     */
    protected function getBehaviorByName(string $name)
    {
        if (array_key_exists($name, $this->behaviors)) {
            return $this->behaviors[$name];
        }

        throw new UnknownBehaviorException($name, DamageTypeBehavior::class);
    }

    protected function getBehaviorByID(int $id)
    {
        $damageType = $this->getDamageTypeByID($id);
        if (is_null($damageType)) {
            throw new \InvalidArgumentException("Cannot find damage-type with ID: " . $id);
        }
        return $this->getBehaviorByName($damageType->name);
    }

    /**
     * @param int $id
     * @return DamageType|null
     */
    public function getDamageTypeByID(int $id)
    {
        return $this->getDamageTypes()->first(function (DamageType $damageType) use ($id) {
            return $damageType->id === $id;
        });
    }

    public function getDamageTypes()
    {
        if (is_null($this->damageTypes)) {
            $this->damageTypes = DamageType::all();
        }
        return $this->damageTypes;
    }
}
