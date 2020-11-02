<?php


namespace App\Services\Models\Reference;


use App\Domain\Models\Material;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MaterialService
 * @package App\Services\Models\Reference
 *
 * @method Material getReferenceModelByID(int $id)
 */
class MaterialService extends ReferenceService
{

    protected function all(): Collection
    {
        return Material::all();
    }

    public function speedModifierBonus(int $materialID)
    {
        return $this->getReferenceModelByID($materialID)->getSpeedModifierBonus();
    }

    public function baseDamageModifierBonus(int $materialID)
    {
        return $this->getReferenceModelByID($materialID)->getBaseDamageModifierBonus();
    }

    public function damageMultiplierModifierBonus(int $materialID)
    {
        return $this->getReferenceModelByID($materialID)->getDamageMultiplierModifierBonus();
    }
}
