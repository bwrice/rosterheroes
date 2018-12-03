<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlotType extends Model
{

    const RIGHT_ARM = 'right-arm';
    const LEFT_ARM = 'left-arm';
    const TORSO = 'torso';
    const HEAD = 'head';
    const FEET = 'feet';
    const HANDS = 'hands';
    const WAIST = 'waist';
    const NECK = 'neck';
    const RIGHT_WRIST = 'right-wrist';
    const LEFT_WRIST = 'left-wrist';
    const RIGHT_RING = 'right-ring';
    const LEFT_RING = 'left-ring';
    const WAGON = 'wagon';
    const UNIVERSAL = 'universal';

    protected $guarded = [];

    public function vectorPaths()
    {
        return $this->morphMany(VectorPath::class, 'has_paths');
    }
}
