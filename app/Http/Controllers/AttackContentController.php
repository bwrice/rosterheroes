<?php

namespace App\Http\Controllers;

use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Http\Request;

class AttackContentController extends Controller
{
    public function create()
    {
        return view('admin.content.attacks.create', [
            'combatPositions' => CombatPosition::all(),
            'targetPriorities' => TargetPriority::all(),
            'damageTypes' => DamageType::all()
        ]);
    }
}
