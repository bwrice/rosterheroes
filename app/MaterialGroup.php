<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
