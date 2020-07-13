<?php

namespace App\Http\Controllers;

use App\Admin\Content\Actions\CreateAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Facades\Content;
use Illuminate\Http\Request;

class AttackContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $attackSources = Content::attacks();
        $totalPages = (int) ceil($attackSources->count()/9);
        return view('admin.content.attacks.index', [
            'attacks' => $attackSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'combatPositions' => CombatPosition::all(),
            'targetPriorities' => TargetPriority::all(),
            'damageTypes' => DamageType::all()
        ]);
    }

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
            'targetsCount' => function ($attribute, $value, $fail) use ($request) {
                /** @var DamageType $damageType */
                $damageType = DamageType::query()->find($request->damageType);
                if ($damageType->name === DamageType::FIXED_TARGET) {
                    if (is_null($value)) {
                        $fail($attribute.' is required for fixed target damage types');
                    } elseif ($value < 1 || $value > 10) {
                        $fail($attribute.' must be between 1 and 10');
                    }
                }
            },
        ]);

        $attackerPosition = CombatPosition::query()->find($request->attackerPosition);
        $targetPosition = CombatPosition::query()->find($request->targetPosition);
        $targetPriority = TargetPriority::query()->find($request->targetPriority);
        $damageType = DamageType::query()->find($request->damageType);

        $createAttack->execute($request->name, $request->tier, $request->targetsCount, $attackerPosition, $targetPosition, $targetPriority, $damageType);

        $request->session()->flash('success', $request->name . ' created');
        return redirect('admin/content/attacks/create');
    }
}
