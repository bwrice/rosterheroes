<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class MaterialGroup extends Model
{
    const HIDE = 'hide';
    const METAL = 'metal';
    const CLOTH = 'cloth';
    const WOOD = 'wood';
    const GEMSTONE = 'gemstone';
    const BONE = 'bone';
    const PRECIOUS_METAL = 'precious-metal';
    const PSIONIC = 'psionic';

    protected $guarded = [];

    public function materialTypes()
    {
        return $this->hasMany(Material::class);
    }
}
