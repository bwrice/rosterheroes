<?php

namespace App\Http\Controllers;

use App\Admin\Content\Actions\CreateAttack;
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

    public function store(Request $request, CreateAttack $createAttack)
    {
        $request->validate([
            'name' => 'required', 'string', 'unique:attacks',
            'tier' => 'required', 'integer', 'between:1,10',
            'attackerPosition' => 'required', 'exists:combat_positions,id',
            'targetPosition' => 'required', 'exists:combat_positions,id',
            'targetPriority' => 'required', 'exists:target_priorities,id',
            'damageType' => 'required', 'exists:damage_types,id',
        ]);

        $attackerPosition = CombatPosition::query()->find($request->attackerPosition);
        $targetPosition = CombatPosition::query()->find($request->targetPosition);
        $targetPriority = TargetPriority::query()->find($request->targetPriority);
        $damageType = DamageType::query()->find($request->damageType);

        $createAttack->execute($request->name, $request->tier, $request->targetsCount, $attackerPosition, $targetPosition, $targetPriority, $damageType);
    }
}
